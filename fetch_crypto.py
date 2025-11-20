import requests
import mysql.connector
from datetime import datetime
from dbcreds import DB_CONFIG

# CoinGecko API ei tarvitse avainta
COINS = ["bitcoin", "ethereum", "cardano"]
CURRENCY = "eur"

# Yhdistä tietokantaan
conn = mysql.connector.connect(**DB_CONFIG)
cursor = conn.cursor()

# Luodaan taulu, jos sitä ei vielä ole
cursor.execute('''
CREATE TABLE IF NOT EXISTS crypto_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coin VARCHAR(50),
    price FLOAT,
    timestamp DATETIME
)
''')

# Poistetaan aiemmat rivit
cursor.execute('DELETE FROM crypto_data')

# Hae hinnat API:sta
for coin in COINS:
    url = f"https://api.coingecko.com/api/v3/simple/price?ids={coin}&vs_currencies={CURRENCY}"
    response = requests.get(url)
    data = response.json()
    price = data[coin][CURRENCY]
    timestamp = datetime.now()
    
    cursor.execute(
        'INSERT INTO crypto_data (coin, price, timestamp) VALUES (%s, %s, %s)',
        (coin, price, timestamp)
    )

conn.commit()
cursor.close()
conn.close()

print(f"Crypto data tallennettu: {[coin for coin in COINS]}")
