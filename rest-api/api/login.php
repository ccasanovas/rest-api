<?php
// headers requeridos
header("Access-Control-Allow-Origin: http://localhost/rest-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Archivos requeridos para conectar a la base de datos
include_once 'config/database.php';
include_once 'objects/usuarios.php';
 
// tomar una nueva conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// instanciar un usuario como objeto
$user = new User($db);
 
// tomar los datos posteados
$data = json_decode(file_get_contents("php://input"));
 
// setear los valores de propiedad de usuarios
$user->email = $data->email;
$email_exists = $user->emailExists();
 
// archivos requeridos para generar un web token json con jwt
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// chequear si el usuario, password e email son correctos
if($email_exists && password_verify($data->password, $user->password)){
 
    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
           "id" => $user->id,
           "firstname" => $user->firstname,
           "lastname" => $user->lastname,
           "email" => $user->email
       )
    );
 
    // setear un código de respuesta
    http_response_code(200);
 
    // generar un token jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "message" => "Logueado satisfactoriamente.",
                "jwt" => $jwt
            )
        );
 
}
 
// fallo en el login
else{
 
    // setear un código de respuesta
    http_response_code(401);
 
    // decirle al usuario que el login falló
    echo json_encode(array("message" => "Error en el ingreso."));
}
?>