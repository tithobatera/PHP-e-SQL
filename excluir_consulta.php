<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID da consulta não fornecido ou inválido.";
    exit();
}

$consulta_id = (int)$_GET['id']; 
$user_id = $_SESSION['user_id']; 

$stmt = $conn->prepare("DELETE FROM consultas WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $consulta_id, $user_id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: perfil_utilizador.php?delete_success=1"); 
    exit();
} else {
    echo "Erro ao excluir a consulta: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
