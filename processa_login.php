<?php
session_start();
include 'config.php'; 

$erro = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);
    $senha = trim($_POST['senha']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: perfil_utilizador.php"); 
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login de Usuários</h1>
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
        
        <form action="processa_login.php" method="POST">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br>

            <button type="submit">Entrar</button>
        </form>

        <p>
            Caso não tenha registro, <a href="registro.php">clique aqui</a> para se registrar.
        </p>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</html>
