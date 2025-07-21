import pickle
import pandas as pd
from flask import Flask, request, jsonify
from flask_cors import CORS
import threading  # Import the threading module
import os

# Import the main function from your retraining script
# We assume retrain_model.py is in the same directory.
try:
    from retrain_model import main as run_retraining
    print("✅ Retraining script imported successfully.")
except ImportError as e:
    run_retraining = None
    print(f"❌ KESALAHAN: Gagal mengimpor 'retrain_model.py'. Fungsi retrain tidak akan tersedia. Error: {e}")

# ==============================================================================
# 1. INISIALISASI APLIKASI FLASK
# ==============================================================================
app = Flask(__name__)
CORS(app)

# ==============================================================================
# 2. MANAJEMEN MODEL (LOADING, RELOADING, DAN LOCKING)
# ==============================================================================
model_filename = 'model_stok_kedelai_final.pkl'
model = None
model_lock = threading.Lock() # Lock to make model loading/reloading thread-safe

def load_model():
    """Loads the model from the pickle file into the global 'model' variable."""
    global model
    try:
        if not os.path.exists(model_filename):
            print(f"🟡 PERINGATAN: File model '{model_filename}' tidak ditemukan. Menjalankan retrain untuk membuatnya...")
            if run_retraining:
                run_retraining() # Try to create the model if it doesn't exist
            else:
                print("❌ KESALAHAN: Script retrain tidak tersedia, tidak bisa membuat model awal.")
                return

        with open(model_filename, 'rb') as file:
            model = pickle.load(file)
        print(f"✅ Model '{model_filename}' berhasil dimuat/dimuat ulang.")
    except FileNotFoundError:
        model = None
        print(f"❌ KESALAHAN: File model '{model_filename}' tidak ditemukan setelah mencoba retrain.")
    except Exception as e:
        model = None
        print(f"❌ KESALAHAN: Terjadi error saat memuat model: {e}")

# Load the model initially when the application starts
with app.app_context():
    load_model()

# ==============================================================================
# 3. BUAT ENDPOINT UNTUK PERAMALAN
# ==============================================================================
@app.route('/forecast', methods=['GET'])
def predict_forecast():
    """
    Endpoint for making stock forecasts for the next few days.
    Accepts a 'days' parameter via the URL. Example: /forecast?days=7
    """
    if model is None:
        return jsonify({"error": "Model peramalan tidak tersedia. Periksa log server."}), 500

    try:
        days_to_forecast = int(request.args.get('days', 7))
    except (ValueError, TypeError):
        return jsonify({"error": "Parameter 'days' harus berupa angka (integer)."}), 400
    
    print(f"Menerima permintaan untuk meramalkan {days_to_forecast} hari ke depan...")

    try:
        # Acquire lock to ensure the model is not being reloaded during prediction
        with model_lock:
            future_forecast = model.forecast(steps=days_to_forecast)
        
        result = []
        for tanggal, nilai in future_forecast.items():
            result.append({
                "tanggal": tanggal.strftime('%Y-%m-%d'),
                "prediksi_stok_kg": round(nilai, 2)
            })
            
        print("✅ Peramalan berhasil dibuat dan dikirim.")
        return jsonify(result)

    except Exception as e:
        print(f"❌ Terjadi kesalahan saat membuat peramalan: {e}")
        return jsonify({"error": f"Terjadi kesalahan internal saat peramalan: {str(e)}"}), 500

# ==============================================================================
# 4. BUAT ENDPOINT BARU UNTUK RETRAINING
# ==============================================================================
@app.route('/retrain', methods=['POST'])
def retrain_model_endpoint():
    """
    Endpoint to trigger model retraining and reload it into memory.
    """
    print("Menerima permintaan untuk me-retrain model...")
    
    if not run_retraining:
        error_msg = "Fungsi retraining tidak tersedia karena script gagal diimpor."
        print(f"❌ {error_msg}")
        return jsonify({"error": error_msg}), 500

    try:
        # Step 1: Run the external retraining script
        run_retraining()
        print("✅ Script retraining selesai dijalankan.")

        # Step 2: Reload the model into memory with a lock
        print("Memuat ulang model yang baru di-train...")
        with model_lock:
            load_model() # This function reloads the global 'model' variable

        if model is None:
             raise Exception("Model menjadi None setelah proses reload.")

        success_msg = "Model berhasil di-train ulang dan dimuat ke memori."
        print(f"✅ {success_msg}")
        return jsonify({"status": "success", "message": success_msg})

    except Exception as e:
        error_msg = f"Terjadi kesalahan saat proses retraining: {e}"
        print(f"❌ {error_msg}")
        return jsonify({"error": error_msg}), 500

# ==============================================================================
# 5. JALANKAN SERVER
# ==============================================================================
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)