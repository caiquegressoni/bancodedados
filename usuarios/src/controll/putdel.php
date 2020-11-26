<?php

	$urlFront="http://localhost/usuarios";
	header("Content-type: application/json; charset=UTF-8"); //Configura a resposta no formato universal JSON
	header("Access-Control-Allow-Origin:".$urlFront);//Configura a origem permitida das requisições
	header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE"); //Configura os verbos http permitidos

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