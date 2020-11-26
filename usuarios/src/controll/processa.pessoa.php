
<?php
require("../domain/pessoa.php"); //Funciona como import do JAVA requer o arquivo de modelo "pessoa.php"
	$pd = new PessoaDAO(); //Objeto da classe PessoaDAO para acesso ao Banco de Dados
	
	include("putdel.php"); //Inclui as variáveis de ambiente $_PUT e $_DELETE
	
	//Tratar as requisições HTTP REST (GET,POST,PUT,DELETE)
	if(!empty($_GET)){ //Se o verbo GET não estiver vazio
		//O id é auto_increment no banco de dados e inicia em 1
		if(isset($_GET["id"])){
			if($_GET["id"]=="0"){//Filtro, o id for igual a 0 vamos listar todas as pessoas
				echo json_encode($pd->readAll());
			} else { //Senão vamos listar somente a pessoa com o id informado
				echo json_encode($pd->read($_GET["id"]));
			}
		}
	}
	
	if(!empty($_POST)){ //Se o verbo POST não estiver vazio
		if(isset($_POST["id"])){ //Se a nova pessoa possuit Id Cria apenas o telefone senão cria a pessoa
			$id = $_POST["id"];
			$tel = $_POST["telefone"];
			$sucesso = $pd->createTel($id,$tel);
			if(!isset($sucesso["erro"])){
				http_response_code(201);
				echo json_encode($sucesso);
			} else {
				http_response_code(400);
				echo json_encode($sucesso);
			}
		}else{
			$pessoa = new Pessoa();//Cria um novo objeto Pessoa (modelo)
			$pessoa->setNome($_POST["nome"]);//Preenche o modelo
			$pessoa->setTelefone($_POST["telefone"]);//Preenche o modelo
			$sucesso = $pd->create($pessoa);
			if(!is_object($sucesso)){
				http_response_code(201);
				echo json_encode($sucesso);
			} else {
				echo '{"mensagem":"Pessoa criada com sucesso"}';
			}
		}
	}
	
	if(!empty($_PUT)){ //Se o verbo PUT não estiver vazio
		if(isset($_PUT["old_telefone"])){
			$id = $_PUT["id"];
			$oldTel = $_PUT["old_telefone"];
			$newTel = $_PUT["new_telefone"];
			$sucesso = $pd->updateTel($id,$oldTel,$newTel);
			if(!isset($sucesso["erro"])){
				http_response_code(202);
				echo json_encode($sucesso);
			} else {
				http_response_code(400);
				echo json_encode($sucesso);
			}
		} else {
			$pessoa = new Pessoa(); //Cria um novo objeto Pessoa (modelo)
			$pessoa->setIdPessoa($_PUT["id"]);//Preenche o modelo
			$pessoa->setNome($_PUT["nome"]);//Preenche o modelo
			if(isset($_PUT["telefone"])){//Se chegar telefone, preenche o modelo
				$pessoa->setTelefone($_PUT["telefone"]);//Preenche o modelo
			}
			$sucesso = $pd->update($pessoa);
			if(is_object($sucesso)){
				http_response_code(202);
				echo '{"mensagem":"Pessoa alterada com sucesso"}';
			} else {
				http_response_code(400);
				echo json_encode($sucesso);
			}
		}
	}
	
	if(!empty($_DELETE)){ //Se o verbo DELETE não estiver vazio
		if(isset($_DELETE["telefone"])){
			$id = $_DELETE["id"];//Recebe o id
			$tel = $_DELETE["telefone"];//Recebe o id
			$sucesso = $pd->delTel($id,$tel);
			if(isset($sucesso["erro"])){
				http_response_code(405);
				echo json_encode($sucesso);
			} else {
				echo '{"mensagem":"Telefone excluído com sucesso"}';
			}	
		}else{
			$id = $_DELETE["id"];//Recebe o id
			$sucesso = $pd->del($id);
			if(isset($sucesso["erro"])){
				http_response_code(405);
				echo json_encode($sucesso);
			} else {
				echo '{"mensagem":"Pessoa excluída com sucesso"}';
			}	
		}
	}
	
?>