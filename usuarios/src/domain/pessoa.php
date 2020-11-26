<?php
	require("conexao.php");//Importa a classe Conexao que utiliza o objeto PDO
	
	//Model ou Domain são a mesma camada, apenas nomes diferentes
	//Normalmente a classe DAO também faz parte da camada Model no padrão MVC

	class Pessoa{//Classe modelo

		//Atributos
		var $idPessoa;
		var $nome;
		var $telefone;
		
		//Métodos GETs && SETs
		function getIdPessoa(){
			return $this->idPessoa;
		}
		function setIdPessoa($idPessoa){
			$this->idPessoa = $idPessoa;
		}
		function getNome(){
			return $this->nome;
		}
		function setNome($nome){
			$this->nome = $nome;
		}
		function getTelefone(){
			return $this->telefone;
		}
		function setTelefone($telefone){
			$this->telefone = $telefone;
		}
	}
	
	class PessoaDAO{//Classe DAO (Data Access Object) com os métodos CRUD

		function create($pessoa){
			$resultado = array(); //Variável que servirá para retorno do sucesso ou fracasso do método
            $query = "INSERT INTO pessoas VALUES (default,'".$pessoa->getNome()."')";
            try{//Tenta conectar ao BD e executar a query
                $con = new Conexao();//Inicia a conexão
                if(Conexao::getInstancia()->exec($query) >= 1){//O método exec de PDO retorna 0 = fracasso, 1 = sucesso, 2 = sucesso parcial
                    $resultado["id"] = Conexao::getInstancia()->lastInsertId();//Pega o id gerado pelo auto_increment do BD
                    $resultado["nome"] = $pessoa->getNome(); //Configura o nome como resultado
                    if($pessoa->getTelefone()!=null){//Como são duas tabelas no BD pessoa e telefone, precisamos de uma nova query
						$this->createTel($resultado["id"],$pessoa->getTelefone());
					}
                }
                $con = null;//Fecha a conexão
            } catch (PDOException $e) {//Caso tenha problemas com a conexão retorna o erro abaixo
                $resultado["erro"] = "Erro ao conectar ao BD";
            }
            return $resultado;
		}

		function readAll(){//Lista toda a visão vw_pessoas
			$pessoas = [];
			$query = "SELECT * FROM vw_pessoas";
			try{
				$con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);//O método query de PDO retorna uma tabela como resultSet
				while($linha = $resultSet->fetchObject()){
					$pessoa = new Pessoa();
					$pessoa->setIdPessoa($linha->id_pessoa);
					$pessoa->setNome($linha->nome);
					$pessoa->setTelefone($linha->telefone);
					$pessoas[] = $pessoa;
				}
				$con = null;
			}catch(PDOException $e){
				$pessoas["erro"] = "Erro de conexão com BD";
			}
			return $pessoas;
		}

		function read($id){//Lista apenas a pessoa especificada pelo ID
			$pessoas = [];
			$query = "SELECT * FROM vw_pessoas WHERE id_pessoa = $id";
			try{
				$con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);
				if($resultSet){
					while($linha = $resultSet->fetchObject()){
						$pessoa = new Pessoa();
						$pessoa->setIdPessoa($linha->id_pessoa);
						$pessoa->setNome($linha->nome);
						$pessoa->setTelefone($linha->telefone);
						$pessoas[] = $pessoa;
					}
				}
				$con = null;
			}catch(PDOException $e){
				$pessoas["erro"] = "Erro de conexão com BD";
			}
			return $pessoas;
		}

		function update($pessoa){//Atualiza os dados no Banco de dados, nas duas tabelas pessoas e telefones
			$resultado = [];
			$id = $pessoa->getIdPessoa();//Configura os parâmetros para montar a query
			$nome = $pessoa->getNome();//Configura os parâmetros para montar a query
			$query = "UPDATE pessoas SET nome = '$nome' WHERE id_pessoa = $id";
			try{
                $con = new Conexao();
				$status = Conexao::getInstancia()->prepare($query);//O método prepare retorna um status se a query estiver correta
				if($status->execute()){//O método execute retorna um boolean true para sucesso e false para fracasso
					$resultado = $pessoa;
                    if($pessoa->getTelefone()!=null){//Como são duas tabelas no BD pessoa e telefone, precisamos de uma nova query
						$resultado = $this->updateTel($id,$pessoa->getTelefone(),$pessoa->getTelefone());
					}					
				} else {
					$resultado["erro"] = "Não foi possível alterar Pessoa";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}

		function del($id){//Método que exclui dados, pode apresentar erro de fluxo de dados também
			//Pois, não configuramos o parâmetro "ON DELETE CASCADE" na tabela de usuários no BD
			//Se a pessoa tiver um usuário atrelado, esta não pode ser excluída
			$resultado = [];
			$query = "DELETE FROM pessoas WHERE id_pessoa=$id";
			try{
				$con = new Conexao();
				if(Conexao::getInstancia()->exec($query)>=1){
					$resultado["mensagem"] = "Pessoa excluída com sucesso";
				} else {
					$resultado["erro"] = "Esta pessoa não pode ser excluída, pois possui um usuário atrelado.";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}

		function createTel($id,$tel){
			$resultado = [];
			$query = "SELECT telefone FROM telefones WHERE id_pessoa = $id AND telefone = '$tel'"; //Query para verificar se a pessoa já possui este telefone
			try{
                $con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);
				if(!$linha = $resultSet->fetchObject()){//Se não possui este telefone então cria-o
					$query = "INSERT INTO telefones VALUES ($id,'$tel')";
					Conexao::getInstancia()->prepare($query)->execute();
					$resultado["mensagem"] = "Telefone criado com sucesso";
				} else {
					$resultado["erro"] = "Telefone já existe";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}

		function updateTel($id,$oldTel,$newTel){
			$resultado = [];
			$query = "SELECT telefone FROM telefones WHERE id_pessoa = $id AND telefone = '$oldTel'"; //Query para verificar se a pessoa já possui este telefone
			try{
                $con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);
				if($linha = $resultSet->fetchObject()){//Se encontrou o telefone antigo atualiza, senão cria
					$query = "UPDATE telefones SET telefone = '$newTel' WHERE id_pessoa = $id AND telefone = '$oldTel'";
					Conexao::getInstancia()->prepare($query)->execute();
					$resultado["mensagem"] = "Telefone alterado com sucesso";
				}else{
					$query = "INSERT INTO telefones VALUES ($id,'$newTel')";
					Conexao::getInstancia()->prepare($query)->execute();
					$resultado["mensagem"] = "Telefone criado com sucesso";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}

		function delTel($id, $tel){
			$resultado = [];
			$query = "DELETE FROM telefones WHERE id_pessoa=$id AND telefone = '$tel'";
			try{
				$con = new Conexao();
				if(Conexao::getInstancia()->exec($query)>=1){
					$resultado["mensagem"] = "Telefone excluído com sucesso";
				} else {
					$resultado["erro"] = "Erro ao excluir telefone";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}
	}