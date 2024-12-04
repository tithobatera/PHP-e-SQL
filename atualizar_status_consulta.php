<?php
session_start();
include 'config.php';

if (isset($_POST['consulta_id']) && isset($_POST['status'])) {
    $consulta_id = $_POST['consulta_id'];
    $status = $_POST['status'];
    $comentario_cancelamento = isset($_POST['comentario_cancelamento']) ? $_POST['comentario_cancelamento'] : '';

    $stmt = $conn->prepare("UPDATE consultas SET status = ?, comentario_cancelamento = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $comentario_cancelamento, $consulta_id);
    $stmt->execute();
    $stmt->close();

    if ($status === 'Aceita') {
        header("Location: perfil_administrador.php");
    } else {
        header("Location: perfil_administrador.php"); 
    }
    exit();
}
?>
