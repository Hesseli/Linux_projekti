const client = mqtt.connect("ws://" + window.location.hostname + ":9001")

const statusEl = document.getElementById("status")
const messages = document.getElementById("messages")
const msgBox = document.getElementById("msgBox")

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

function sendMessage() {
    const text = msgBox.value.trim()
    if (!text) return

    client.publish("chat/messages", text)
    msgBox.value = ""
}
