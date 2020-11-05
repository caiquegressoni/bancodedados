<?php
    require("../domain/pessoa.php");
    header("Content-type: application/json");

    $pd = new PessoaDAO();
   $pessoas = $pd->readAll();
   echo json_encode($pessoas);
?>
