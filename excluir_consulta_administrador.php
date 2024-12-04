<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); 
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID da consulta não fornecido ou inválido.";
    exit();
}

$consulta_id = (int) $_GET['id']; 

$stmt = $conn->prepare("DELETE FROM consultas WHERE id = ?");
if ($stmt === false) {
    echo "Erro na preparação da consulta: " . htmlspecialchars($conn->error);
    exit();
}

$stmt->bind_param("i", $consulta_id);

if ($stmt->execute()) {
    header("Location: perfil_administrador.php?delete_success=1");
} else {
    echo "Erro ao excluir a consulta: " . htmlspecialchars($stmt->error);
}


$stmt->close();
$conn->close();
?>
