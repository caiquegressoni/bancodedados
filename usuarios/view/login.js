const msg = document.querySelector("#mensagem");
const mensagem = location.search.slice(1);
if (mensagem.split("=")[0] == "erro"){
    msg.innerHTML = decodeURIComponent(mensagem.split("=")[1]);
    setTimeout(() => { window.location.href = "?" }, 3000);
}