async function loadMessages() {
    const r = await fetch('/cicd/api/messages');
    const data = await r.json();
    const ul = document.getElementById('messages');
    ul.innerHTML = '';
    data.forEach(m => {
        const li = document.createElement('li');
        li.textContent = `${m.name}: ${m.message}`;
        ul.appendChild(li);
    });
}

document.getElementById('form').onsubmit = async (e) => {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const message = document.getElementById('msg').value;

    await fetch('/cicd/api/messages', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({name, message})
    });

    loadMessages();
    e.target.reset();
};

loadMessages();
