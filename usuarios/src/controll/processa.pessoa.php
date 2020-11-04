<?php
    require("../domain/pessoa.php");
    header("Content-type: application/json");

   /* $p1 = new Pessoa();
    
    $p1->setIdPessoa(1);
    $p1->setNome("André Silva");
    $p1->setTelefone("19 6060-60600");

    var_dump($p1); 

   echo $p1->getIdPessoa()." ";
   echo $p1->getNome()." ";
   echo $p1->getTelefone();*/ 

   $pessoas = [];

   $pessoa = new Pessoa();
   $pessoa->setIdPessoa(1);
   $pessoa->setNome("André Silva");
   $pessoa->setTelefone("19 6060-60600");
   $pessoas[0] = $pessoa;

   $pessoa = new Pessoa();
   $pessoa->setIdPessoa(2);
   $pessoa->setNome("Spider Silva");
   $pessoa->setTelefone("19 6060-60600");
   $pessoas[1] = $pessoa;
   
   $pessoa = new Pessoa();
   $pessoa->setIdPessoa(3);
   $pessoa->setNome("Dumbo");
   $pessoa->setTelefone("19 6060-60600");
   $pessoas[2] = $pessoa;

   //var_dump($pessoas);

   /*for($i = 0; $i<count($pessoas);$i++){
       echo $pessoas[$i]->getIdPessoa();
       echo $pessoas[$i]->getNome();
       echo $pessoas[$i]->getTelefone();
   }*/

   foreach($pessoas as $p){
    echo $p->getIdPessoa()." ";
    echo $p->getNome()." ";
    echo $p->getTelefone()."<br>";
   }

   echo json_encode($pessoas);
?>
