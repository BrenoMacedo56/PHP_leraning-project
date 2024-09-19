<?php
$server = "Localhost";
$user = "root";
$password = "";
$dbname = "cadastro_filmes";

$conn = new mysqli($server, $user, $password, $dbname);
if($conn->connect_error){
    die("Falha na conexão".$conn->connect_error);
}
?>