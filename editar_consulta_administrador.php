<?php
session_start();
include 'config.php'; // Conexão com o banco de dados

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Redireciona para a página inicial se não estiver logado
    exit();
}

// Verifica se o ID da consulta foi passado pela URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID da consulta não fornecido ou inválido.";
    exit();
}

$consulta_id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM consultas WHERE id = ?");
$stmt->bind_param("i", $consulta_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Consulta não encontrada.";
    exit();
}

$consulta = $result->fetch_assoc(); 
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Editar Consulta</h1>
    </header>

    <nav>
        <ul>
            <li><a href="perfil_administrador.php">Perfil administrativo</a></li>
        </ul>
    </nav>

    <section>
        <form action="atualizar_consulta.php" method="POST">
            <input type="hidden" name="consulta_id" value="<?php echo htmlspecialchars($consulta['id']); ?>">

            <br>
            <label for="especialidade">Especialidade:</label>
            <input type="text" name="especialidade" id="especialidade" value="<?php echo htmlspecialchars($consulta['especialidade']); ?>" required>
            
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" id="data_hora" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($consulta['data_hora']))); ?>" required>

            <label for="observacoes">Observações:</label>
            <textarea name="observacoes" id="observacoes"><?php echo htmlspecialchars($consulta['observacoes']); ?></textarea>

            <button type="submit">Atualizar Consulta</button>
        </form>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</html>
