<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta_id = (int)$_POST['consulta_id'];
    $especialidade = trim($_POST['especialidade']);
    $data_hora = $_POST['data_hora'];
    $observacoes = trim($_POST['observacoes']);

    $stmt = $conn->prepare("UPDATE consultas SET especialidade = ?, data_hora = ?, observacoes = ? WHERE id = ?");
    $stmt->bind_param("sssi", $especialidade, $data_hora, $observacoes, $consulta_id);

    if ($stmt->execute()) {
        header("Location: perfil_administrador.php?update_success=1");
    } else {
        echo "Erro ao atualizar a consulta: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
