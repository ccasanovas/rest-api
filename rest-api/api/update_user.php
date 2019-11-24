<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// archivos requeridos para generar token jwt
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// conexión a la base de datos
include_once 'config/database.php';
include_once 'objects/usuarios.php';
 
// tomar la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// instanciar como un objeto los usuarios
$user = new User($db);
 
// traer los datos posteados
$data = json_decode(file_get_contents("php://input"));
 
// traer jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
 
// si jwt no está vacío
if($jwt){
 
    // Si se realiza el decode mostrar datos de usuario
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 // setear los valores del objeto usuario
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$user->id = $decoded->data->id;
 
// Crear el producto
if($user->update()){
// necesitamos regenerar jwt porque los detalles de usuario podrían ser diferentes
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
$jwt = JWT::encode($token, $key);
 
// setear código de respuesta
http_response_code(200);
 
// respuesta en formato json
echo json_encode(
        array(
            "message" => "El usuario fue modificado.",
            "jwt" => $jwt
        )
    );

}

 
// Mensaje si no es posible modificar el usuario
else{
    // setear código de respuesta
    http_response_code(401);
 
    // mostrar mensaje de error
    echo json_encode(array("message" => "No se pudo actualizar el usuario."));
}

    }
 
// si el decode de json falla, significa que el token jwt no es válido
catch (Exception $e){
 
    // setear código de respuesta
    http_response_code(401);
 
    // mostrar mensaje de error
    echo json_encode(array(
        "message" => "Acceso denegado.",
        "error" => $e->getMessage()
    ));
}
}
 
// Mostrar mensaje de error si JWT está vacío
else{
 
    // mostrar código de respuesta
    http_response_code(401);
 
    // decirle al usuario que tiene el acceso denegado
    echo json_encode(array("message" => "Acceso denegado."));
}
?>