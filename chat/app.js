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
            div.innerHTML = `<strong>${msg.sender || 'vierailija'}:</strong> ${msg.message}`
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
    const txt = message.toString()
    const [sender, ...rest] = txt.split(":")
    const msgText = rest.join(":")
    const msg = document.createElement("div")
    msg.innerHTML = `<strong>${sender || 'vierailija'}:</strong> ${msgText}`
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

    // Lähetetään vain viesti
    try {
        const res = await fetch("/chat/save_messages.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `message=${encodeURIComponent(text)}`
        })
        const data = await res.json()
        if (data.success) {
            // Julkaistaan MQTT:llä palvelimen palauttama viesti
            client.publish("chat/messages", `${data.sender}:${text}`)
        }
    } catch (e) {
        console.error("Viestiä ei voitu tallentaa:", e)
    }

    msgBox.value = ""
}
