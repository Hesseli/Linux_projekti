<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>LEMP Demo – Kellonaika SQL:stä</title>
    <style>
        /* Yleiset tyylit */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center; /* Keskittää sisältöä yleisesti */
            background-color: #f4f4f9; /* Vaalea tausta */
            color: #333; /* Tekstin väri */
        }

        /* Otsikot */
        h1, h2 {
            text-align: center; /* Varmistaa otsikoiden keskityksen */
            color: #3f51b5; /* Sininen väri otsikoille */
        }

        /* Kellonaika-elementin tyylit */
        #time {
            display: inline-block; /* Antaa elementille koon sisällön mukaan, mutta sallii keskityksen */
            font-size: 3em; /* Tekee kellosta isomman */
            font-weight: bold;
            margin: 20px auto; /* Lisää pystysuuntaista tilaa ja varmistaa vaakasuuntaisen keskityksen */
            padding: 15px 30px;
            border: 2px solid #90a4ae; /* Kehys kellon ympärille */
            border-radius: 10px; /* Pyöristetyt kulmat */
            background-color: #ffffff; /* Valkoinen tausta kellolle */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Kevyt varjo */
            color: #e91e63; /* Erottuva väri kellonajalle */
        }

        /* Linkin tyylit */
        a {
            display: block; /* Tekee linkistä lohkotason elementin */
            margin-top: 30px;
            padding: 10px 20px;
            text-decoration: none; /* Poistaa alleviivauksen */
            background-color: #4caf50; /* Vihreä tausta linkille */
            color: white;
            border-radius: 5px;
            width: fit-content; /* Antaa leveyden sisällön mukaan */
            margin-left: auto; /* Keskittää linkin vaakaan */
            margin-right: auto; /* Keskittää linkin vaakaan */
            transition: background-color 0.3s ease; /* Animaatio hiirellä */
        }

        a:hover {
            background-color: #388e3c; /* Tummempi vihreä hiirellä */
        }
    </style>
</head>
<body>

<h1>Tervetuloa LEMP -sivulle!</h1>

<h2>SQL-palvelimen aika:</h2>
<div id="time">Ladataan...</div>

<script>
async function loadTime() {
    // Huom: Tämä olettaa, että fetch_time.php palauttaa JSON-muodossa { "server_time": "hh:mm:ss" }
    try {
        const res = await fetch("fetch_time.php");
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        const data = await res.json();
        document.getElementById("time").innerText = data.server_time;
    } catch (error) {
        console.error("Error loading time:", error);
        document.getElementById("time").innerText = "Virhe ladattaessa";
    }
}

// Hakee ajan sivun latautuessa
loadTime();

// Päivittää 1 sek välein
setInterval(loadTime, 1000);
</script>

<a href="/data-analysis">Avaa data-analyysi</a>

</body>
</html>