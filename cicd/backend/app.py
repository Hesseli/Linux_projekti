from flask import Flask, request, jsonify
import json, os

app = Flask(__name__)

DATA_FILE = "/data/messages.json"

if not os.path.exists(DATA_FILE):
    with open(DATA_FILE, 'w') as f:
        json.dump([], f)

@app.route("/messages", methods=["GET", "POST"])
def messages():
    with open(DATA_FILE, 'r') as f:
        data = json.load(f)

    if request.method == "POST":
        body = request.json
        data.append(body)
        with open(DATA_FILE, 'w') as f:
            json.dump(data, f)
        return {"status": "ok"}

    return jsonify(data)

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
