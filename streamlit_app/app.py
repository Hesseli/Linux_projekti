import streamlit as st
import mysql.connector
import pandas as pd

st.title("Data Analysis Dashboard")

cursor = conn.cursor()
cursor.execute("SELECT NOW() AS server_time")
result = cursor.fetchone()
st.write("Database time:", result[0])

st.markdown("### Esimerkkidata taulusta:")
query = "SELECT * FROM some_table LIMIT 10"
df = pd.read_sql(query, conn)
st.dataframe(df)
