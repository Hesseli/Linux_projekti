<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>LEMP Demo – Kellonaika SQL:stä</title>
</head>
<body>

<h1>Tervetuloa Nginx-serverillä pyörivälle HTML sivulle!</h1>
<p>Tämän sivun versio tulee GitHubista.</p>

<h2>SQL-palvelimen aika:</h2>
<div id="time">Ladataan...</div>

<script>
async function loadTime() {
    const res = await fetch("fetch_time.php");
    const data = await res.json();
    document.getElementById("time").innerText = data.server_time;
}

// Hakee ajan sivun latautuessa
loadTime();

// Päivittää 1 sek välein
setInterval(loadTime, 1000);
</script>

</body>
</html>
