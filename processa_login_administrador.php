<?php
session_start();
include 'config.php';

$erro = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);
    $senha = trim($_POST['senha']); 

    $stmt = $conn->prepare("SELECT * FROM administradores WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        
        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: perfil_administrador.php");
            exit();
        } 
    } else {
        $erro = "Usuário não encontrado.";
    }

    $stmt->close();
}
?>

<?php
include 'config.php';

$usuario = "admin"; 
$nova_senha = password_hash("123456", PASSWORD_DEFAULT); 


$stmt = $conn->prepare("UPDATE administradores SET senha = ? WHERE usuario = ?");
$stmt->bind_param("ss", $nova_senha, $usuario);

if ($stmt->execute()) {
    echo "Senha do administrador autenticada, faça o login novamente.";
} else {
    echo "Erro ao atualizar a senha: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login do Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login do Administrador</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>

    <section>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form action="processa_login_administrador.php" method="POST">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br>

            <button type="submit">Entrar</button>
        </form>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</html>
