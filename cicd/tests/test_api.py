import requests

def test_root():
    r = requests.get("http://backend:5000/messages")
    assert r.status_code == 200
