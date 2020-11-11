<?php

$urlFront="http://localhost/usuarios";
header("Access-Control-Allow-Origin:".$urlFront);
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");

$_DELETE = array();
	$_PUT = array();
	if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
		parse_str(file_get_contents('php://input'), $_DELETE);
	}
	if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
		parse_str(file_get_contents('php://input'), $_PUT);
    }
?> 