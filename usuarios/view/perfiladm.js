const tableUsuario = document.querySelector("#bodyUsuarios");
const tablePessoa = document.querySelector("#bodyPessoas");
const urlPessoa = "http://localhost/usuarios/src/controll/processa.pessoa.php?id=0";
const urlUsuario = "http://localhost/usuarios/src/controll/processa.usuario.php?id=0";
const urlId = location.search.slice(1).split("=")[2];
const urlLogin = location.search.slice(1).split("&")[0].split("=")[1];
const nome = document.querySelector("#nome");
const login = document.querySelector("#login");
const telefones = document.querySelector("#telefones");
const urlPerfil = "http://localhost/usuarios/src/controll/processa.pessoa.php?id="+urlId;

function carregarPerfil() {
    alert("Passei aqui");
    fetch(urlPerfil)
        .then(function (resp) {
            //Obtem a resposta da URL no formato JSON
            if (!resp.ok)
                throw new Error("Erro ao executar requisição: " + resp.status);
            return resp.json();
        })
        .then(function (data) {
            //Se obteve a resposta explora os dados recebidos
            data.forEach((val) => {
                let telefone = document.createElement("input");
                telefone.value = val.telefone;
                nome.value = val.nome;
                login.value = urlLogin;
                telefones.appendChild(telefone);
            });
        }) //Se obteve erro no processo exibe no console do navegador
        .catch(function (error) {
            console.error(error.message);
        });
}
function carregaPessoas() {
    fetch(urlPessoa)
        .then(function (resp) {
            //Obtem a resposta da URL no formato JSON
            if (!resp.ok)
                throw new Error("Erro ao executar requisição: " + resp.status);
            return resp.json();
        })
        .then(function (data) {
            //Se obteve a resposta explora os dados recebidos
            data.forEach((val) => {
                let row = document.createElement("td");
                row.innerHTML = `<td>${val.idPessoa}</td>`;
                row.innerHTML += `<td>${val.nome}</td>`;
                row.innerHTML += `<td>${val.telefone}</td>`;
                row.innerHTML += `<td style="padding:3px"><button onclick='editPessoa(this)'>Edit</button><button onclick='delPessoa(this)'>Del</button></td>`;
                tablePessoa.appendChild(row);
            });
        }) //Se obteve erro no processo exibe no console do navegador
        .catch(function (error) {
            console.error(error.message);
        });
}

function carregaUsuarios() {
    fetch(urlUsuario)
        .then(function (resp) {
            //Obtem a resposta da URL no formato JSON
            if (!resp.ok)
                throw new Error("Erro ao executar requisição: " + resp.status);
            return resp.json();
        })
        .then(function (data) {
            //Se obteve a resposta explora os dados recebidos
            data.forEach((val) => {
                let row = document.createElement("td");
                row.innerHTML = `<td>${val.idPessoa}</td>`;
                row.innerHTML += `<td>${val.login}</td>`;
                row.innerHTML += `<td>${val.tipo}</td>`;
                row.innerHTML += `<td style="padding:3px"><button onclick='editUsuario(this)'>Edit</button><button onclick='delUsuario(this)'>Del</button></td>`;
                tableUsuario.appendChild(row);
            });
        }) //Se obteve erro no processo exibe no console do navegador
        .catch(function (error) {
            console.error(error.message);
        });
}
function sair(){
    window.location.href = "http://localhost/usuarios";
}