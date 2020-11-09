<?php
    require("conexao.php");

    class Usuario{

        var $idPessoas;
        var $login;
        var $senha;
        var $tipo;

        function getIdPessoa(){
			return $this->idPessoa;
		}
		function setIdPessoa($idPessoa){
			$this->idPessoa = $idPessoa;
		}
		function getLogin(){
			return $this->login;
		}
		function setLogin($login){
			$this->login = $login;
		}
		function getSenha(){
			return $this->senha;
		}
		function setSenha($senha){
			$this->senha = $senha;
        }
        function getTipo(){
			return $this->tipo;
		}
		function setTipo($tipo){
			$this->tipo = $tipo;
		}
    }
    class UsuarioDAO{//Classe DAO (Data Access Object) com os métodos CRUD

		function create($usuario){
            $resultado = array();
          $id = $usuario->getIdPessoa();
          $login = $usuario->getLogin();
          $senha = $usuario->getSenha();
          $tipo = $usuario->getTipo();

			$resultado = array(); //Variável que servirá para retorno do sucesso ou fracasso do método
            $query = "INSERT INTO usuarios VALUES ($id,'$login', md5('$senha'),'$tipo')";
            try{//Tenta conectar ao BD e executar a query
                $con = new Conexao();//Inicia a conexão
                if(Conexao::getInstancia()->exec($query) >=1){
                    $resultado[] = $usuario;
                }
                $con = null;//Fecha a conexão
            } catch (PDOException $e) {//Caso tenha problemas com a conexão retorna o erro abaixo
                $resultado["erro"] = "Erro ao conectar ao BD";
            }
            return $resultado;
        }
        function readAll(){//Lista todo o usuario
			$usuarios = [];
			$query = "SELECT * FROM usuarios";
			try{
				$con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);//O método query de PDO retorna uma tabela como resultSet
				while($linha = $resultSet->fetchObject()){
					$usuario = new Usuario();
					$usuario->setIdPessoa($linha->id_pessoa);
					$usuario->setLogin($linha->login);
                    $usuario->setSenha($linha->senha);
                    $usuario->setTipo($linha->tipo);
					$usuarios[] = $usuario;
				}
				$con = null;
			}catch(PDOException $e){
				$usuarios["erro"] = "Erro de conexão com BD";
			}
			return $usuarios;
        }
        function read($id){//Lista todo o usuario
			$usuario = [];
			$query = "SELECT * FROM usuarios where id_pessoas = $id";
			try{
				$con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);//O método query de PDO retorna uma tabela como resultSet
				while($linha = $resultSet->fetchObject()){
					$usuario = new Usuario();
					$usuario->setIdPessoa($linha->id_pessoa);
					$usuario->setLogin($linha->login);
                    $usuario->setSenha($linha->senha);
                    $usuario->setTipo($linha->tipo);
					$usuarios[] = $usuario;
				}
				$con = null;
			}catch(PDOException $e){
				$usuarios["erro"] = "Erro de conexão com BD";
			}
			return $usuarios;
        }
        function readLogin($login){//Lista todo o usuario
			$usuario = [];
			$query = "SELECT * FROM usuarios where login = '$login%'";
			try{
				$con = new Conexao();
				$resultSet = Conexao::getInstancia()->query($query);//O método query de PDO retorna uma tabela como resultSet
				while($linha = $resultSet->fetchObject()){
					$usuario = new Usuario();
					$usuario->setIdPessoa($linha->id_pessoa);
					$usuario->setLogin($linha->login);
                    $usuario->setSenha($linha->senha);
                    $usuario->setTipo($linha->tipo);
					$usuarios[] = $usuario;
				}
				$con = null;
			}catch(PDOException $e){
				$usuarios["erro"] = "Erro de conexão com BD";
			}
			return $usuarios;
        }
        function update($usuario){//Atualiza os dados no Banco de dados, nas duas tabelas pessoas e telefones
			$resultado = [];
			$login = $usuario->getLogin();//Configura os parâmetros para montar a query
            $senha = $usuario->getSenha();//Configura os parâmetros para montar a query
            $tipo = $usuario->getTipo();
			$query = "UPDATE usuarios SET senha = md5('$senha'), tipo='$tipo where login = '$login'";
			
            try{
                $con = new Conexao();
				$status = Conexao::getInstancia()->prepare($query);//O método prepare retorna um status se a query estiver correta
				if($status->execute()){//O método execute retorna um boolean true para sucesso e false para fracasso
                    $resultado[] = $usuario;
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
        }
        function del($login){//Método que exclui dados, pode apresentar erro de fluxo de dados também
			//Pois, não configuramos o parâmetro "ON DELETE CASCADE" na tabela de telefones no BD
			//Caso a pessoa possua um ou mais telefones, esta não será excluída
			$resultado = [];
			$query = "DELETE FROM usuarios WHERE login ='$login'";
			try{
				$con = new Conexao();
				if(Conexao::getInstancia()->exec($query)>=1){
					$resultado["msg"] = "Usuario Apagad";
				}
				$con = null;
			}catch(PDOException $e){
				$resultado["erro"] = "Erro de conexão com o BD";	
			}
			return $resultado;
		}
    }