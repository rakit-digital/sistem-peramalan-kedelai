import pickle
import pandas as pd
from flask import Flask, request, jsonify

# ==============================================================================
# 1. INISIALISASI APLIKASI FLASK
# ==============================================================================
app = Flask(__name__)

# ==============================================================================
# 2. MUAT MODEL SAAT APLIKASI DIMULAI
# ==============================================================================
# Ini adalah cara yang efisien, model hanya dimuat sekali saat server start.
model_filename = 'model_stok_kedelai_final.pkl'
try:
    with open(model_filename, 'rb') as file:
        model = pickle.load(file)
    print(f"✅ Model '{model_filename}' berhasil dimuat.")
except FileNotFoundError:
    model = None
    print(f"❌ KESALAHAN: File model '{model_filename}' tidak ditemukan.")
    print("Pastikan file model berada di direktori yang sama dengan app.py")


# ==============================================================================
# 3. BUAT ENDPOINT UNTUK PERAMALAN
# ==============================================================================
# Endpoint ini akan dapat diakses melalui URL: http://127.0.0.1:5000/forecast
@app.route('/forecast', methods=['GET'])
def predict_forecast():
    """
    Endpoint untuk membuat peramalan stok untuk beberapa hari ke depan.
    Menerima parameter 'days' melalui URL. Contoh: /forecast?days=7
    """
    # Periksa apakah model sudah berhasil dimuat
    if model is None:
        return jsonify({"error": "Model tidak tersedia. Periksa log server."}), 500

    # Ambil jumlah hari dari parameter URL. Jika tidak ada, defaultnya 7 hari.
    try:
        days_to_forecast = int(request.args.get('days', 7))
    except (ValueError, TypeError):
        return jsonify({"error": "Parameter 'days' harus berupa angka (integer)."}), 400
    
    print(f"Menerima permintaan untuk meramalkan {days_to_forecast} hari ke depan...")

    try:
        # Lakukan peramalan menggunakan model yang sudah dimuat
        future_forecast = model.forecast(steps=days_to_forecast)
        
        # Format hasil menjadi JSON yang ramah untuk web (list of dictionaries)
        result = []
        for tanggal, nilai in future_forecast.items():
            result.append({
                "tanggal": tanggal.strftime('%Y-%m-%d'), # Format tanggal agar standar
                "prediksi_stok_kg": round(nilai, 2)     # Bulatkan nilai prediksi
            })
            
        print("✅ Peramalan berhasil dibuat dan dikirim.")
        return jsonify({
            "status": "success",
            "forecast": result,
            "days_requested": days_to_forecast
        })

    except Exception as e:
        print(f"❌ Terjadi kesalahan saat membuat peramalan: {e}")
        return jsonify({"error": f"Terjadi kesalahan internal: {e}"}), 500

# ==============================================================================
# 4. JALANKAN SERVER
# ==============================================================================
if __name__ == '__main__':
    # host='0.0.0.0' agar bisa diakses dari luar localhost (misal dari HP di jaringan yg sama)
    # debug=True untuk mode pengembangan, server akan auto-restart jika ada perubahan kode
    app.run(host='0.0.0.0', port=5000, debug=True)