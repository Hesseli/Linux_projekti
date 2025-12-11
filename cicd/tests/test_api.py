import requests
import time

def test_backend_messages():
    # Odotetaan hetki, että backend ehtii käynnistyä GH-ympäristössä
    time.sleep(2)

    r = requests.get("http://localhost:5000/messages")
    assert r.status_code == 200
    
    data = r.json()
    assert isinstance(data, list)
