<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// archivos necesarios para establecer conexión con la base de datos
include_once 'config/database.php';
include_once 'objects/user.php';
 
// tomar la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// instanciar un nuevo usuario como objeto
$user = new User($db);
 
// tomar los datos posteados
$data = json_decode(file_get_contents("php://input"));
 
// setear las propiedades del objeto usuario
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
 
// crear el usuario
if(
    !empty($user->firstname) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
){
 
    // setear un código de respuesta
    http_response_code(200);
 
    // mostrar mensaje en caso de crear el usuario
    echo json_encode(array("message" => "El usuario fue creado."));
}
 
// mensaje si no es posible crear el usuario
else{
 
    // setear código de respuesta
    http_response_code(400);
 
    // mostrar mensaje en caso de no poder crear el usuario
    echo json_encode(array("message" => "No se pudo crear el usuario."));
}
?>