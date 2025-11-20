import requests
import mysql.connector
from datetime import datetime
from dbcreds import DB_CONFIG, OWM_API_KEY


API_KEY = 'OWM_API_KEY'
CITY = 'Helsinki'
URL = f'https://api.openweathermap.org/data/2.5/weather?q={CITY}&appid={API_KEY}&units=metric'

# Yhdistetään MariaDB:hen
conn = mysql.connector.connect(**DB_CONFIG)
cursor = conn.cursor()

# Luodaan taulu, jos sitä ei vielä ole
cursor.execute('''
CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(50),
    temperature FLOAT,
    description VARCHAR(100),
    timestamp DATETIME
)
''')

# Poistetaan aiemmat rivit
cursor.execute('DELETE FROM weather_data')

# Haetaan data API:sta
response = requests.get(URL)
data = response.json()
temp = data['main']['temp']
desc = data['weather'][0]['description']
timestamp = datetime.now()

# Lisätään data tietokantaan
cursor.execute(
    'INSERT INTO weather_data (city, temperature, description, timestamp) VALUES (%s, %s, %s, %s)',
    (CITY, temp, desc, timestamp)
)

conn.commit()
cursor.close()
conn.close()

print(f'Data tallennettu: {CITY} {temp}°C {desc}')
