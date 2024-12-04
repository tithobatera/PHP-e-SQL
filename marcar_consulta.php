<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telefone = htmlspecialchars(trim($_POST['telefone']));
    $especialidade = htmlspecialchars(trim($_POST['especialidade']));
    $data_hora = htmlspecialchars(trim($_POST['data_hora']));
    $observacoes = htmlspecialchars(trim($_POST['observacoes']));

    if (empty($nome) || empty($email) || empty($telefone) || empty($especialidade) || empty($data_hora)) {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO consultas (user_id, nome_completo, email, telefone, especialidade, data_hora, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) { 
            $stmt->bind_param("issssss", $user_id, $nome, $email, $telefone, $especialidade, $data_hora, $observacoes);

            if ($stmt->execute()) {
                echo "<script>alert('Consulta marcada com sucesso!'); window.location.href = 'perfil_utilizador.php';</script>";
                exit();
            } else {
                echo "<script>alert('Erro ao marcar consulta: " . $stmt->error . ". Tente novamente.');</script>";
            }
            $stmt->close(); 
        } else {
            echo "<script>alert('Erro ao preparar a consulta: " . $conn->error . ". Tente novamente.');</script>";
        }
    }
    $conn->close(); 
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcar Consulta</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Marcar Consulta</h1>
    </header>

    <nav>
        <ul>
            <li><a href="perfil_utilizador.php">Voltar ao Perfil</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </nav>

    <section>
        <form method="POST" action="marcar_consulta.php">
            <label for="nome">Nome completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>
            <br>
            <label for="especialidade">Especialidade:</label>
            <select id="especialidade" name="especialidade" required>
                <option value="Cardiologia">Cardiologia</option>
                <option value="Dermatologia">Dermatologia</option>
                <option value="Ginecologia">Ginecologia</option>
                <option value="Ortopedia">Ortopedia</option>
                <option value="Pediatria">Pediatria</option>
            </select>
        <br>
            <label for="data_hora">Data e Hora da Consulta:</label>
            <br>
            <input type="datetime-local" id="data_hora" name="data_hora" required>
            <br>
            <label for="observacoes">Observações:</label>
            <textarea id="observacoes" name="observacoes"></textarea>

            <button type="submit">Agendar Consulta</button>
        </form>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>