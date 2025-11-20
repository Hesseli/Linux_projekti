#!/bin/bash

VENV_DIR="/home/ubuntu/cron_assignment/venv"
WORKDIR="/home/ubuntu/cron_assignment"

cd $WORKDIR

if [ ! -d "$VENV_DIR" ]; then
    echo "Luodaan virtuaaliympäristö..."
    python3 -m venv $VENV_DIR
fi

# Aktivoi virtuaaliympäristö
. $VENV_DIR/bin/activate

if [ -f "requirements.txt" ]; then
    echo "Asennetaan riippuvuudet..."
    pip install --upgrade pip
    pip install -r requirements.txt
else
    echo "requirements.txt ei löytynyt!"
fi

if [ -f "fetch_weather.py" ]; then
    echo "Suoritetaan fetch_weather.py..."
    python fetch_weather.py
else
    echo "fetch_weather.py ei löytynyt!"
fi

echo "Valmis!"
