<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='perfil_administrador.php';</script>";
        exit();
    } else {
        echo "Erro ao excluir usuário: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: perfil_administrador.php");
    exit();
}

$conn->close();
?>
