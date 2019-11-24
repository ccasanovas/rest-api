<?php
// clase para tomar conexión con la base de datos
class Database{
 
    // credenciales de la base de datos
    private $host = "localhost";
    private $db_name = "api_db";
    private $username = "root";
    private $password = "";
    public $conn;
 
    // tomar la conexión de la base de datos
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Error de conexión: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>