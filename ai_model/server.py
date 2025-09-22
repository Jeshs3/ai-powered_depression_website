from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from model import predict_depression

app = FastAPI()

#Allow CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],            # or restrict with ["http://localhost", "http://127.0.0.1:5500"]
    allow_credentials=True,
    allow_methods=["*"],            # allow all HTTP methods
    allow_headers=["*"],            # allow all headers
)

class InputData(BaseModel):
    answers: list[str]

@app.post("/prediction")
def predict(data: InputData):
    try:
        prediction, probability = predict_depression(data.answers)
        return {
            "status": prediction,
            "probability": probability
        }
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
