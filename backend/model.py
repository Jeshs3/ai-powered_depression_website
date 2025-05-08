import joblib
import json
import sys
import os

BASE_DIR = os.path.dirname(__file__)
model_path = os.path.join(BASE_DIR, "depression (rf).pkl")
vectorizer_path = os.path.join(BASE_DIR, "tfidf_vectorizer.pkl")
vectorizer = joblib.load(vectorizer_path)
model = joblib.load(model_path)

with open('../script/question.json', 'r', encoding='utf-8') as f:
    emotion_data = json.load(f)

def predict_depression(emotion_values):
    input_texts = []
    print("Debug - Input emotion values:", emotion_values, file=sys.stderr)

    for emotion_value in emotion_values:
        found = False
        for question in emotion_data:
            for emotion in question['emotions']:
                if emotion['value'] == emotion_value:
                    input_texts.append(question['text'] + " " + emotion['label'])
                    found = True
                    break
            if found:
                break
        if not found:
            raise ValueError(f"Unknown emotion value: {emotion_value}")

    print("Debug - Generated input texts:", input_texts, file=sys.stderr)

    x = vectorizer.transform(input_texts)
    probas = model.predict_proba(x)
    print("All probs:", probas, file=sys.stderr)

    avg_prob = sum(p[1] for p in probas) / len(probas)
    prediction = 1 if avg_prob > 0.5 else 0
    print("Debug - Avg probability:", avg_prob, file=sys.stderr)

    return int(prediction), float(avg_prob)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python model.py emotion1,emotion2,emotion3,...", file=sys.stderr)
        sys.exit(1)

    input_str = " ".join(sys.argv[1:])
    emotion_values = [val.strip() for arg in sys.argv[1:] for val in arg.split(",") if val.strip()]

    try:
        prediction, probability = predict_depression(emotion_values)
        # FINAL JSON OUTPUT — only this goes to stdout
        print(json.dumps({
            "status": prediction,
            "probability": round(probability, 4)
        }))
    except ValueError as e:
        print(f"Error: {e}", file=sys.stderr)
        sys.exit(1)
