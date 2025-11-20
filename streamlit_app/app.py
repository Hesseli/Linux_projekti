import streamlit as st
import mysql.connector
import pandas as pd
from dbcreds import DB_CONFIG

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
st.dataframe(df_weather)

conn.close()
