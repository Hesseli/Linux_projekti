import streamlit as st
import mysql.connector
import pandas as pd
from dbcreds import DB_CONFIG

st.set_page_config(page_title="Data Dashboard", layout="wide")

st.title("Tervetuloa Streamlit-sovellukseen")

# Yhdistä tietokantaan
conn = mysql.connector.connect(**DB_CONFIG)

# --- Vanha data ---
st.markdown("### Tietokannan aika")
df_time = pd.read_sql("SELECT NOW() AS server_time", conn)
st.dataframe(df_time)

st.markdown("### Esimerkkidata taulusta")
df_example = pd.read_sql("SELECT * FROM some_table LIMIT 10", conn)
st.dataframe(df_example)

# --- Säädata ---
st.markdown("### Viimeisimmät säätiedot Helsingistä")
df_weather = pd.read_sql("SELECT * FROM weather_data ORDER BY timestamp DESC LIMIT 10", conn)

# Käytetään Pandas Stylen taulukon väritykseen
df_weather_display = df_weather.style.background_gradient(subset=["temperature"], cmap="RdYlBu_r") \
                                     .set_properties(**{'text-align': 'center'})
st.dataframe(df_weather_display)

# --- Kryptodata ---
st.markdown("### Viimeisimmät kryptohinnat")
# Oletetaan, että tietokannassa on taulu crypto_data (tai voit hakea API:sta)
# Tässä esimerkki Pandas DataFrame: 
crypto_data = pd.DataFrame([
    {"nimi": "Bitcoin", "hinta_eur": 27200},
    {"nimi": "Ethereum", "hinta_eur": 1850},
    {"nimi": "Cardano", "hinta_eur": 0.33},
])

cols = st.columns(len(crypto_data))
for idx, row in crypto_data.iterrows():
    cols[idx].metric(label=row['nimi'], value=f"{row['hinta_eur']} €")

conn.close()
