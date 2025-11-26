const client = mqtt.connect("ws://86.50.21.73:9001")

const statusEl = document.getElementById("status")
const messages = document.getElementById("messages")
const msgBox = document.getElementById("msgBox")

// Ladataan viimeiset viestit palvelimelta
async function loadMessages() {
    try {
        const res = await fetch("/chat/get_messages.php")
        const data = await res.json()
        messages.innerHTML = ""
        data.forEach(msg => {
            const div = document.createElement("div")
            div.textContent = msg.message
            messages.appendChild(div)
        })
        messages.scrollTop = messages.scrollHeight
    } catch (e) {
        console.error("Viestejä ei voitu ladata:", e)
    }
}

loadMessages()

client.on("connect", () => {
    statusEl.textContent = "Yhdistetty MQTT-palvelimeen"
    statusEl.className = "status-green"

    client.subscribe("chat/messages")
})

client.on("message", (topic, message) => {
    const msg = document.createElement("div")
    msg.textContent = message.toString()
    messages.appendChild(msg)
    messages.scrollTop = messages.scrollHeight
})

client.on("error", () => {
    statusEl.textContent = "MQTT-yhteysvirhe"
    statusEl.className = "status-red"
})

async function sendMessage() {
    const text = msgBox.value.trim()
    if (!text) return

    // Lähetetään MQTT:llä
    client.publish("chat/messages", text)

    // Tallennetaan tietokantaan
    try {
    await fetch("/chat/save_messages.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `message=${encodeURIComponent(text)}`
    })

    } catch (e) {
        console.error("Viestiä ei voitu tallentaa:", e)
    }

    msgBox.value = ""
}
