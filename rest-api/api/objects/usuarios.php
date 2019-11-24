<?php
// Clase usuarios
class User{
 
    // conexión a la base de datos y nombre de la tabla usuarios
    private $conn;
    private $table_name = "users";
 
    // declaro como públicas las propiedades del objeto
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// Crear un nuevo registro de usuarios
function create(){
 
    // query para insertar
    $query = "INSERT INTO " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";
 
    // preparar la consulta
    $stmt = $this->conn->prepare($query);
 
    // sanitizar la consulta
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
 
    // enlazar los valores 
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
 
    // hash para el password antes de guardar en la base de datos
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
 
    // ejecutar la consulta
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

 
// chequear si el email existe en la base de datos
function emailExists(){
 
    // consulta para chequear la existencia del email
    $query = "SELECT id, firstname, lastname, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
 
    // preparo la consulta
    $stmt = $this->conn->prepare( $query );
 
    // sanitizo la consulta
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // enlazo el valor dado en email
    $stmt->bindParam(1, $this->email);
 
    // ejecuto la consulta
    $stmt->execute();
 
    // tomo la cantidad de rows de la tabla
    $num = $stmt->rowCount();
 
    // si el email existe, asigno valores al objeto usuario para acceder facilmente y utilizar sesiones en caso que tenga que hacerlo
    if($num>0){
 
        //  tomo lo detalles del registro
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // asigno los valores a las propiedades del objeto
        $this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['password'];
 
        // return true si el email existe en la base de datos
        return true;
    }
 
    // returno false en caso que no lo haga
    return false;
}
 
// actualizar un registro de usuario
public function update(){
 
    // el password es requerido para actualizar el registro
    $password_set=!empty($this->password) ? ", password = :password" : "";
 
    // si no es posteado el password no se actualiza
    $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email
                {$password_set}
            WHERE id = :id";
 
    // preparo la consulta
    $stmt = $this->conn->prepare($query);
 
    // sanitizo los valores
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // enlazo los valores del formulario
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
 
    // hash para el password antes de guardar en la base de datos
    if(!empty($this->password)){
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    }
 
    // enlazo el ID del valor que será editado
    $stmt->bindParam(':id', $this->id);
 
    // ejecuto la consulta
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
}