import os
import pandas as pd
import numpy as np
import mysql.connector
from dotenv import load_dotenv
from statsmodels.tsa.holtwinters import ExponentialSmoothing
import pickle
from sklearn.metrics import mean_squared_error

# Load environment variables from a .env file (for local testing) or from the container's environment
load_dotenv()

# --- Database Connection Details from Environment Variables ---
DB_HOST = os.getenv("DB_HOST", "mysql")
DB_DATABASE = os.getenv("DB_DATABASE")
DB_USERNAME = os.getenv("DB_USERNAME")
DB_PASSWORD = os.getenv("DB_PASSWORD")
MODEL_FILENAME = 'model_stok_kedelai_final.pkl'

def get_data_from_db():
    """Fetches soybean usage data from the Laravel database."""
    print("Connecting to database...")
    try:
        connection = mysql.connector.connect(
            host=DB_HOST,
            database=DB_DATABASE,
            user=DB_USERNAME,
            password=DB_PASSWORD
        )
        # We predict usage, so we select 'usage_kg'
        query = "SELECT date, usage_kg FROM soybean_stocks ORDER BY date ASC"
        df = pd.read_sql(query, connection, index_col='date', parse_dates=['date'])
        connection.close()
        print(f"Successfully fetched {len(df)} records from the database.")
        
        # Ensure daily frequency and interpolate missing values
        df = df.asfreq('D')
        df['usage_kg'] = df['usage_kg'].interpolate(method='linear')
        return df
    except Exception as e:
        print(f"Error connecting to database or fetching data: {e}")
        return None

def find_best_model_config(data):
    """Searches for the best Holt-Winters configuration."""
    print("Finding best model configuration...")
    train_size = int(len(data) * 0.8)
    train, test = data.iloc[:train_size], data.iloc[train_size:]

    if len(test) == 0:
        print("Not enough data to create a test set. Using default config.")
        return ('add', 'add', 7) # Return a sensible default

    configs = []
    for trend_type in ['add', 'mul']:
        for seasonal_type in ['add', 'mul']:
            for p in [7, 14]: # Weekly and bi-weekly seasonality
                try:
                    model = ExponentialSmoothing(train['usage_kg'], trend=trend_type, seasonal=seasonal_type, seasonal_periods=p, initialization_method='estimated').fit()
                    forecast = model.forecast(steps=len(test))
                    rmse = np.sqrt(mean_squared_error(test['usage_kg'], forecast))
                    configs.append(((trend_type, seasonal_type, p), rmse))
                except Exception:
                    continue
    
    if not configs:
         print("Could not find a valid model configuration. Using default.")
         return ('add', 'add', 7)

    best_config, _ = min(configs, key=lambda item: item[1])
    print(f"Best configuration found: {best_config}")
    return best_config


def main():
    """Main function to run the retraining pipeline."""
    data = get_data_from_db()
    if data is None or data.empty:
        print("No data available to train the model. Exiting.")
        return

    # 1. Find the best hyperparameters based on the latest data
    best_trend, best_seasonal, best_p = find_best_model_config(data)

    # 2. Train the final model on ALL available data
    print("Retraining final model on all data...")
    final_model = ExponentialSmoothing(
        data['usage_kg'],
        trend=best_trend,
        seasonal=best_seasonal,
        seasonal_periods=best_p,
        initialization_method='estimated'
    ).fit()
    print("Model retraining complete.")

    # 3. Save the newly trained model to a file
    with open(MODEL_FILENAME, 'wb') as file:
        pickle.dump(final_model, file)
    print(f"✅ Model successfully saved to '{MODEL_FILENAME}'")

if __name__ == "__main__":
    main()
    