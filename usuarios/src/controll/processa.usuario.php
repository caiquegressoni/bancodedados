<?php
    require("../domain/usuario.php"); //Funciona como import do JAVA requer o arquivo de modelo "pessoa.php"
	header("Content-type: application/json"); //Configura a resposta no formato universal JSON
	$ud = new UsuarioDAO(); //Objeto da classe PessoaDAO para acesso ao Banco de Dados

	//No PHP somente as constantes $_GET e $_POST já existem por padrão
	//Os vetores/constantes DELETE e PUT precisam ser criados 
	$_DELETE = array();
	$_PUT = array();
	if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
		parse_str(file_get_contents('php://input'), $_DELETE);
	}
	if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
		parse_str(file_get_contents('php://input'), $_PUT);
	}
	
	//Tratar as requisições HTTP REST (GET,POST,PUT,DELETE)
	if(!empty($_GET)){ //Se o verbo GET não estiver vazio
		//O id é auto_increment no banco de dados e inicia em 1
        if($_GET["id"]=="0"){//Filtro, o id for igual a 0 vamos listar todas as pessoas
            if(empty($_GET["login"])){
			echo json_encode($ud->readAll());
        } else{
            echo json_encode($ud->readLogin($_GET["login"]));
        }
          } else { //Senão vamos listar somente a pessoa com o id informado
			echo json_encode($ud->read($_GET["id"]));
		}
	}
	
	if(!empty($_POST)){ //Se o verbo POST não estiver vazio
		$usuario = new Usuario();//Cria um novo objeto Pessoa (modelo)
		$usuario->setLogin($_POST["login"]);//Preenche o modelo
        $usuario->setSenha($_POST["senha"]);//Preenche o modelo
        $usuario->setTipo($_PUT["tipo"]);
		echo json_encode($ud->create($usuario));//Executa o método create de DAO passando o modelo como parâmetro
	}
	if(!empty($_PUT)){ //Se o verbo PUT não estiver vazio
		$usuario = new usuario(); //Cria um novo objeto Pessoa (modelo)
		$usuario->setIdPessoa($_PUT["id"]);//Preenche o modelo
        $usuario->setLogin($_POST["login"]);
        $usuario->setSenha($_PUT["senha"]);//Preenche o modelo
		$usuario->setTipo($_PUT["tipo"]);//Preenche o modelo
		echo json_encode($ud->update($usuario));//Executa o método update de DAO passando o modelo como parâmetro
	}
	
	if(!empty($_DELETE)){ //Se o verbo DELETE não estiver vazio
		$id = $_DELETE["id"];//Recebe o id
		echo json_encode($ud->del($id));//Executa o método del de DAO passando o id como parâmetro
	}
	