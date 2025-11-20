import sys
import os

sys.path.append(os.path.expanduser('~/cron_assignment'))

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
df_time = df_time.drop(columns=["id"], errors="ignore")
st.dataframe(df_time, hide_index=True)

st.markdown("### Esimerkkidata taulusta")
df_example = pd.read_sql("SELECT * FROM some_table LIMIT 10", conn)
st.dataframe(df_example, hide_index=True)

# --- Säädata ---
st.markdown("### Viimeisimmät säätiedot Helsingistä")
df_weather = pd.read_sql("SELECT * FROM weather_data ORDER BY timestamp DESC LIMIT 10", conn)
df_weather = df_weather.drop(columns=["id"], errors="ignore")
df_weather["temperature"] = df_weather["temperature"].astype(float)

df_weather_display = (
    df_weather.style
    .background_gradient(subset=["temperature"], cmap="RdYlBu_r")
    .format({"temperature": "{:.1f}"})
    .set_properties(**{'text-align': 'center'})
)

st.dataframe(df_weather_display, hide_index=True)


# --- Kryptodata ---
st.markdown("### Viimeisimmät kryptohinnat")
# Luetaan kryptodata kannasta
df_crypto = pd.read_sql("SELECT coin AS nimi, price AS hinta_eur FROM crypto_data ORDER BY id", conn)
# Näytetään dynaamisesti
cols = st.columns(len(df_crypto))
for idx, row in df_crypto.iterrows():
    cols[idx].metric(label=row['nimi'], value=f"{row['hinta_eur']} €")

conn.close()