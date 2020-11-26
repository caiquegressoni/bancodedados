<?php
    require("../domain/usuario.php"); //Funciona como import do JAVA requer o arquivo de modelo "pessoa.php"
	$ud = new UsuarioDAO(); //Objeto da classe PessoaDAO para acesso ao Banco de Dados

	include("putdel.php"); //Inclui as variáveis de ambiente $_PUT e $_DELETE

	//Tratar as requisições HTTP REST (GET,POST,PUT,DELETE)
	if(!empty($_GET)){ //Se o verbo GET não estiver vazio
		//O id é auto_increment no banco de dados e inicia em 1
		if($_GET["id"]=="0"){//Filtro, o id for igual a 0 vamos listar todas as pessoas
			if(empty($_GET["login"])){
				echo json_encode($ud->readAll());
			} else {
				echo json_encode($ud->readLogin($_GET["login"]));
			}			
		} else { //Senão vamos listar somente a pessoa com o id informado
			echo json_encode($ud->read($_GET["id"]));
		}
	}
	
	if(!empty($_POST)){ //Se o verbo POST não estiver vazio
		if(!empty($_POST["id"])){
			$usuario = new Usuario();//Cria um novo objeto Pessoa (modelo)
			$usuario->setIdPessoa($_POST["id"]);//Preenche o modelo
			$usuario->setLogin($_POST["login"]);//Preenche o modelo
			$usuario->setSenha($_POST["senha"]);//Preenche o modelo
			$usuario->setTipo($_POST["tipo"]);//Preenche o modelo
			$status = $ud->create($usuario);
			if(is_object($status)){
				http_response_code(201); //Código HTTP de criação de dados
				echo '{"mensagem":"Usuário criado com sucesso"}'; //Mensagem de sucesso JSON
			} else {
				echo json_encode($status); //Mensagem de erro JSON
			}
		}
		if(!empty($_POST["login"])&&!empty($_POST["senha"])&&empty($_POST["id"])){
			$login = $_POST["login"];
			$senha = $_POST["senha"];
			$status = $ud->login($login,$senha);
			if(!is_object($status)){
				http_response_code(401); //Código HTTP de acesso negado (Senha ou usuario não conferem)
			}
			echo json_encode($status); //Mensagem de erro ou dados de login em caso de sucesso
		}
	}
	
	if(!empty($_PUT)){ //Se o verbo PUT não estiver vazio
		$usuario = new Usuario();//Cria um novo objeto Pessoa (modelo)
		$usuario->setLogin($_PUT["login"]);//Preenche o modelo
		$usuario->setSenha($_PUT["senha"]);//Preenche o modelo
		$usuario->setTipo($_PUT["tipo"]);//Preenche o modelo
		$status = $ud->update($usuario); //Executa o método update de DAO passando o modelo como parâmetro
		if(is_object($status)){ //Se receber um Objeto como retorno é porque a alteração deu certo
			echo '{"mensagem":"Usuário alterado com sucesso"}'; //Mensagem de sucesso JSON
		} else {
			echo json_encode($status); //Se não é objeto, veio uma mensagem de erro JASON
		}
	}
	
	if(!empty($_DELETE)){ //Se o verbo DELETE não estiver vazio
		$login = $_DELETE["login"];//Recebe o id
		$status = $ud->del($login); //Executa o método del de DAO passando o login como parâmetro
		if(is_object($status)){ //Se receber um Objeto como retorno é porque a exclusão deu certo
			echo '{"mensagem":"Usuário excluído com sucesso"}'; //Mensagem de sucesso JSON
		} else {
			echo json_encode($status); //Se não é objeto, veio uma mensagem de erro JASON
		}
	}
?>