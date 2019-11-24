<?php
// mosrar errores
error_reporting(E_ALL);
 
// setear la zona horaria
date_default_timezone_set('America/Argentina/Buenos_aires');
 
// variables usadas para jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;
?>