if (location.search.slice(1).split("=")[0] == "erro") {
    erro = decodeURIComponent(location.search.slice(1).split("=")[1]);
    window.location.href = "./view/login.html?erro=" + erro;
} else {
    if (location.search.slice(1).split("=")[0] == "login") {
        login = decodeURIComponent(location.search.slice(1).split("=")[1].split("&")[0]);
        tipo = decodeURIComponent(location.search.slice(2).split("=")[2].split("&")[0]);
        id = decodeURIComponent(location.search.slice(3).split("=")[3]);
        if (tipo === "adm") {
            window.location.href = "./view/perfiladm.html?login=" + login + "&id=" + id;
        } else {
            window.location.href = "./view/perfil.html?login=" + login + "&id=" + id;
        }
    } else {
        window.location.href = "./view/login.html";
    }
}