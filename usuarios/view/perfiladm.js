const msg = document.querySelector("#mensagem");
const xhr = new XMLHttpRequest();
const tableUsuario = document.querySelector("#bodyUsuarios");
const tablePessoa = document.querySelector("#bodyPessoas");
const urlPessoa = "../src/controll/processa.pessoa.php?id=0";
const urlUsuario = "../src/controll/processa.usuario.php?id=0";
const urlId = location.search.slice(1).split("=")[2];
const urlLogin = location.search.slice(1).split("&")[0].split("=")[1];
const nome = document.querySelector("#nome");
const login = document.querySelector("#login");
const telefones = document.querySelector("#telefones");
const urlPerfil = "../src/controll/processa.pessoa.php?id=" + urlId;
let contTel = 0;
let tels = [];

function carregaPerfil() {
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
                nome.value = val.nome;
                login.value = urlLogin;
                if (val.telefone != null) {
                    let telefone = document.createElement("input");
                    let edit = document.createElement("button");
                    let del = document.createElement("button");
                    telefone.setAttribute("id", "tel" + contTel);
                    edit.innerHTML = "Edit";
                    edit.setAttribute("onclick", "editTel(tel" + contTel + ","+contTel+")");
                    del.innerHTML = "Del";
                    del.setAttribute("onclick", "delTel(tel" + contTel + ")");
                    telefone.value = val.telefone;
                    telefones.appendChild(telefone);
                    telefones.appendChild(edit);
                    telefones.appendChild(del);
                    tels[contTel] = val.telefone;
                    contTel++;
                }
            });
            let add = document.createElement("button");
            add.innerHTML = "Add";
            add.setAttribute("onclick", "addTel()");
            telefones.appendChild(add);
        }) //Se obteve erro no processo exibe no console do navegador
        .catch(function (error) {
            console.error(error.message);
        });
}
function carregaPessoas() {
    if (urlId !== undefined) {
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
                    let row = document.createElement("tr");
                    row.innerHTML = `<tr><td>${val.idPessoa}</td>`;
                    row.innerHTML += `<td>${val.nome}</td>`;
                    row.innerHTML += `<td>${val.telefone}</td>`;
                    row.innerHTML += `<td style="padding:3px"><button onclick='editPessoa(this)'>Edit</button><button onclick='delPessoa(this)'>Del</button></td></tr>`;
                    tablePessoa.appendChild(row);
                });
            }) //Se obteve erro no processo exibe no console do navegador
            .catch(function (error) {
                console.error(error.message);
            });
    }
}
function carregaUsuarios() {
    if (urlId !== undefined) {
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
                    let row = document.createElement("tr");
                    row.innerHTML = `<tr><td>${val.idPessoa}</td>`;
                    row.innerHTML += `<td>${val.login}</td>`;
                    row.innerHTML += `<td>${val.tipo}</td>`;
                    row.innerHTML += `<td style="padding:3px"><button onclick='editUsuario(this)'>Edit</button><button onclick='delUsuario(this)'>Del</button></td></tr>`;
                    tableUsuario.appendChild(row);
                });
            }) //Se obteve erro no processo exibe no console do navegador
            .catch(function (error) {
                console.error(error.message);
            });
    }
}
function sair() {
    window.location.href = "../";
}
function atualizaPerfil(tipo) {
    let url = "../src/controll/processa.usuario.php";
    let dados = "";
    msg.innerHTML = "";

    //Envia alteração de senha
    let senha = document.getElementById("senha");
    let cSenha = document.getElementById("csenha");
    if (senha.value != "" || cSenha.value != "") {
        if (senha.value === cSenha.value) {
            dados = "login=" + urlLogin;
            dados += "&senha=" + senha.value;
            dados += "&tipo=" + tipo;
            xhr.addEventListener("readystatechange", function () {
                if (this.readyState === this.DONE) {
                    msg.innerHTML += "Senha atualizada com sucesso,<br/>";
                }
            });
            xhr.open("PUT", url);
            xhr.send(dados);
        } else {
            msg.innerHTML = "A confirmação de senha deve ser igual a senha.<br>";
        }
    }

    //Envia alteração de nome
    url = "../src/controll/processa.pessoa.php";
    dados = "id=" + urlId;
    dados += "&nome=" + nome.value;
    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === this.DONE) {
            let resp = JSON.parse(this.responseText);
            if (resp.hasOwnProperty("erro")) {
                msg.innerHTML += resp.erro;
            } else {
                msg.innerHTML += resp.mensagem;
            }
        }
    });
    xhr.open("PUT", url);
    xhr.send(dados);
    setTimeout(() => { window.location.reload(); }, 3000);
}

function criaPessoa() {
    let url = "../src/controll/processa.pessoa.php";
    let nom = document.querySelector("#nomePessoa");
    let telefone = document.querySelector("#telPessoa");
    if (nom.value != "" && telefone.value != "") {
        let dados = new FormData();
        dados.append("nome", nom.value);
        dados.append("telefone", telefone.value);
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Pessoa criada com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("POST", url);
        xhr.send(dados);
    } else {
        msg.innerHTML = "Favor preencher o nome e o telefone.";
        setTimeout(() => { msg.innerHTML = "Mensagens do sistema"; }, 3000);
    }
}

function editPessoa(p) {
    p.parentNode.parentNode.cells[1].setAttribute("contentEditable", "true");
    p.parentNode.parentNode.cells[2].setAttribute("contentEditable", "true");
    p.parentNode.parentNode.cells[3].innerHTML = "<button onclick='putPessoa(this)'>Enviar</button>";
}

function putPessoa(p) {
    let url = "../src/controll/processa.pessoa.php";
    let id = p.parentNode.parentNode.cells[0].innerHTML;
    let nom = p.parentNode.parentNode.cells[1].innerHTML;
    let tel = p.parentNode.parentNode.cells[2].innerHTML;
    let dados = "id=" + id;
    dados += "&nome=" + nom;
    dados += "&telefone=" + tel;
    if (window.confirm("Confirma Alteração dos dados?")) {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Pessoa alterada com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("PUT", url);
        xhr.send(dados);
    }
}


function delPessoa(p) {
    let url = "../src/controll/processa.pessoa.php";
    let id = p.parentNode.parentNode.cells[0].innerText;
    let dados = "id=" + id;
    if (window.confirm("Confirma Exclusão do id " + id + "?")) {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Pessoa excluída com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("DELETE", url);
        xhr.send(dados);
    }
}

function criaUsuario() {
    let url = "../src/controll/processa.usuario.php";
    let id = document.querySelector("#idUser");
    let loginUser = document.querySelector("#loginUser");
    let senha = document.querySelector("#senhaUser");
    let tipo = document.querySelector("#tipoUser");
    if (id.value != "" && loginUser.value != "" && senha.value != "") {
        let dados = new FormData();
        dados.append("id", id.value);
        dados.append("login", loginUser.value);
        dados.append("senha", senha.value);
        dados.append("tipo", tipo.value);
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Usuário criado com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("POST", url);
        xhr.send(dados);
    } else {
        msg.innerHTML = "Favor preencher o id, login e a senha.";
        setTimeout(() => { msg.innerHTML = "Mensagens do sistema"; }, 3000);
    }
}

function editUsuario(u) {
    u.parentNode.parentNode.cells[2].innerHTML = "<input type='password' placeholder='Reset Senha' id='senh'>";
    u.parentNode.parentNode.cells[2].innerHTML += "<select id='tip'><option value='comum'>Comum</option><option value='adm'>Admin</option></select>";
    u.parentNode.parentNode.cells[3].innerHTML = "<button onclick='putUsuario(this)'>Enviar</button>";
}

function putUsuario(u) {
    let url = "../src/controll/processa.usuario.php";
    let login = u.parentNode.parentNode.cells[1].innerHTML;
    let senh = document.querySelector("#senh");
    let tip = document.querySelector("#tip");
    let dados = "login=" + login;
    dados += "&senha=" + senh.value;
    dados += "&tipo=" + tip.value;
    if (window.confirm("Confirma Alteração dos dados?")) {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Usuário alterado com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("PUT", url);
        xhr.send(dados);
    }
}

function delUsuario(u) {
    let url = "../src/controll/processa.usuario.php";
    let loginUser = u.parentNode.parentNode.cells[1].innerText;
    let dados = "login=" + loginUser;
    if (window.confirm("Confirma Exclusão do login " + loginUser + "?")) {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Usuário excluído com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        });
        xhr.open("DELETE", url);
        xhr.send(dados);
    }
}

function editTel(t,indice) {
    let url = "../src/controll/processa.pessoa.php";
    let dados = "id="+urlId;
    dados += "&old_telefone="+tels[indice];
    dados += "&new_telefone="+t.value;
    if (tels[indice] != t.value && t.value != "") {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Telefone alterado com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 500);
            }
        });
        xhr.open("PUT", url);
        xhr.send(dados);
    }
}

function delTel(t) {
    let url = "../src/controll/processa.pessoa.php";
    let dados = "id=" + urlId;
    dados += "&telefone=" + t.value;
    if (window.confirm("Confirma Exclusão do telefone " + t.value + "?")) {
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Telefone excluído com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 500);
            }
        });
        xhr.open("DELETE", url);
        xhr.send(dados);
    }
}

function addTel() {
    let telefone = document.createElement("input");
    telefone.setAttribute("id", "tel" + contTel);
    let send = document.createElement("button");
    send.innerHTML = "Enviar";
    send.setAttribute("onclick", "sendTel(tel" + contTel + ")");
    telefones.appendChild(telefone);
    telefones.appendChild(send);
    contTel++;
}

function sendTel(t) {
    let url = "../src/controll/processa.pessoa.php";
    if (t.value != "") {
        let dados = new FormData();
        dados.append("id", urlId);
        dados.append("telefone", t.value);
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === this.DONE) {
                let resp = JSON.parse(this.responseText);
                if (resp.hasOwnProperty("erro")) {
                    msg.innerHTML = resp.erro;
                } else {
                    msg.innerHTML = "Telefone criado com sucesso.";
                }
                setTimeout(() => { window.location.reload(); }, 500);
            }
        });
        xhr.open("POST", url);
        xhr.send(dados);
    } else {
        msg.innerHTML = "Favor preencher o telefone.";
        setTimeout(() => { msg.innerHTML = "Mensagens do sistema"; }, 500);
    }
}