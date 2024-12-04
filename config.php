<?php

$servername = "localhost"; 
$username = "root"; 
$password = "Titho@1810"; 
$dbname = "sistema_marcacao_consultas"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
