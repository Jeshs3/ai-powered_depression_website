from flask import Flask, request, jsonify
import joblib
import numpy as np

# Load model and scaler
model = joblib.load("depression_model.pkl")
scaler = joblib.load("scaler.pkl")

app = Flask(__name__)

@app.route("/predict", methods=["POST"])
def predict():
    data = request.json["responses"]  # Get test responses
    data = np.array(data).reshape(1, -1)  # Convert to array
    data = scaler.transform(data)  # Normalize inputs

    prediction = model.predict(data)[0]  # Make prediction
    result = "Depressed" if prediction == 1 else "Not Depressed"
    
    return jsonify({"result": result})

if __name__ == "__main__":
    app.run(debug=True)
