<?php
$servername = "localhost"; 
$username = "root"; 
$password = "Titho@1810"; 
$dbname = "sistema_marcacao_consultas"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING);
    $apelido = filter_var(trim($_POST['apelido']), FILTER_SANITIZE_STRING);
    $telefone = filter_var(trim($_POST['telefone']), FILTER_SANITIZE_STRING);
    $data_nascimento = $_POST['data_nascimento'];
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);
    $senha = trim($_POST['senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);

    if ($senha !== $confirmar_senha) {
        die("As senhas não coincidem.");
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Usuário ou email já cadastrados.");
    }
    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, apelido, telefone, data_nascimento, email, usuario, senha) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome, $apelido, $telefone, $data_nascimento, $email, $usuario, $senha_hash);

    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso!";
        header("Location: login.php");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
