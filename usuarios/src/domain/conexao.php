<?php
	class Conexao{
		
		public static $instancia;
		
		public static function getInstancia(){
			$url = "mysql:host=localhost;port=3306;dbname=usuarios";
			$usuario = "root";
			$senha = "";
			$config = array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_CASE => PDO::CASE_LOWER);
			if (!isset(self::$instancia)) {
				self::$instancia = new PDO($url, $usuario,$senha,$config); 
				self::$instancia->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
			}
			return self::$instancia;
		}
	}
