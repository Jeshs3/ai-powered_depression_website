import joblib
import json
import sys
import os

# Load model
#model = joblib.load("depression(rf).pkl")
BASE_DIR = os.path.dirname(__file__)
model_path = os.path.join(BASE_DIR, "depression (rf).pkl")
vectorizer_path = os.path.join(BASE_DIR, "tfidf_vectorizer.pkl")
vectorizer = joblib.load(vectorizer_path)
model = joblib.load(model_path)

# Load JSON file containing the questions and emotions
with open('../script/question.json', 'r', encoding='utf-8') as f:
    emotion_data = json.load(f)

def predict_depression(emotion_values):
    input_texts = []

    for emotion_value in emotion_values:
        found = False
        for question in emotion_data:  # Iterate through the list of questions
            for emotion in question['emotions']:  # Iterate through the emotions list in each question
                if emotion['value'] == emotion_value:
                    input_texts.append(question['text'] + " " + emotion['label'])
                    found = True
                    break
            if found:
                break
        if not found:
            raise ValueError(f"Unknown emotion value: {emotion_value}")
    
    x = vectorizer.transform(input_texts)

    prediction = model.predict(x)[0]
    probability = model.predict_proba(x)[0][1] if hasattr(model, 'predict_proba') else -1

    return int(prediction), float(probability)


# If called from CLI
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python model.py withdrawn,socially_engaged,...")
        sys.exit(1)

    input_str = sys.argv[1]  # e.g. "withdrawn,socially_engaged"
    emotion_values = input_str.strip().split(",")

    try:
        prediction, probability = predict_depression(emotion_values)
        print(f"{prediction},{probability:.4f}")
    except ValueError as e:
        print(f"Error: {e}")
        sys.exit(1)
