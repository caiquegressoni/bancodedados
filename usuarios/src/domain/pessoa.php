<?php
    require("conexao.php");

class Pessoa{

    var $idPessoa;
    var $nome;
    var $telefone;

    function getIdPessoa()
    {
        return $this->idPessoa;
    }
    function setIdPessoa($id)
    {
        $this->idPessoa = $id;
    }
    function getNome()
    {
        return $this->nome;
    }
    function setNome($nom)
    {
        $this->nome = $nom;
    }
    function getTelefone()
    {
        return $this->telefone;
    }
    function setTelefone($tel)
    {
        $this->telefone = $tel;
    }
}
    class PessoaDAO{
        //Criação do CRUD
        
        function create(){}
        function readAll(){
            $pessoas = [];
            $query = "SELECT * FROM vw_pessoas";
            try{
                $con = new Conexao();//$con cria a conexão
                $resultSet = Conexao::getInstancia()->query($query);
                while($linha = $resultSet->fetchObject()){
                    $pessoa = new Pessoa();
                    $pessoa->setIdPessoa($linha->id_pessoa);
                    $pessoa->setNome($linha->nome);
                    $pessoa->setTelefone($linha->telefone);
                    $pessoas[] = $pessoa;
                }
                $con = null;
            }catch(PDOException $e){
                $pessoas["erro"] = "Erro ao conectar com BD";
            }

            return $pessoas;
        }
        function update(){}
        function del(){}
    }
