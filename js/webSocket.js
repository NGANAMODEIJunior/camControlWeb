const socket = new WebSocket('ws://192.168.65.135:9002');

// Événement déclenché lorsqu'une connexion est ouverte
socket.addEventListener('open', (event) => {
  console.log('Connexion WebSocket ouverte');
});

// Événement déclenché lorsqu'un message est reçu du serveur
socket.addEventListener('message', (event) => {
  console.log('Message reçu du serveur:', event.data);
});

// Fonction pour envoyer un message au serveur WebSocket
function sendMessage(message) {
  socket.send(message);
}
