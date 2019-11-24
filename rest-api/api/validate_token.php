<?php
// headers requeridos
header("Access-Control-Allow-Origin: http://localhost/rest-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Archivos necesarios para validar con JWT
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// Tomar los datos pasados por el POST
$data = json_decode(file_get_contents("php://input"));
 
// Tomar JWT
$jwt=isset($data->jwt) ? $data->jwt : "";
 
// Si JWT no está vacío
if($jwt){
 
    // Si se realiza el decode, mostrar datos de usuario
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // Setear código de respuesta
        http_response_code(200);
 
        // Mostrar detalles de usuario
        echo json_encode(array(
            "message" => "Acceso autorizado.",
            "data" => $decoded->data
        ));
 
    }
 
// Si el decode falla significa que el código JWT no es válido
catch (Exception $e){
 
    // Setear código de respuesta
    http_response_code(401);
 
    // Mostrar que el ingreso fue denegado con un mensaje de error 
    echo json_encode(array(
        "message" => "Acceso denegado.",
        "error" => $e->getMessage()
    ));
}}
 
// mostrar un error si JWT se encuentra vacío
else{
 
    // Setear código de respuesta
    http_response_code(401);
 
    // Decirle al usuario que tiene el acceso denegado
    echo json_encode(array("message" => "Acceso denegado."));
}
?>