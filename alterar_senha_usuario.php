<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php");
    exit();
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = trim($_POST['senha_atual']);
    $nova_senha = trim($_POST['nova_senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);

     if ($nova_senha !== $confirmar_senha) {
        $erro = "A nova senha e a confirmação não coincidem.";
    } else {
        $user_id = $_SESSION['user_id']; 

        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); 
        $stmt->close();

        if (password_verify($senha_atual, $user['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt->bind_param("si", $nova_senha_hash, $user_id); 

            if ($stmt->execute()) {
                $sucesso = "Senha do usuário atualizada com sucesso!";
            } else {
                $erro = "Erro ao atualizar a senha: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $erro = "A senha atual está incorreta.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<htang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha do Usuário</title> 
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Alterar Senha do Usuário</h1> 
    </header>
    <nav>
        <ul>
            <li><a href="perfil_utilizador.php">Home</a></li>
        </ul>
    </nav>

    <section>
        <?php if (!empty($sucesso)): ?>
            <div class="sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php elseif (!empty($erro)): ?>
            <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form action="alterar_senha_usuario.php" method="POST">
            <label for="senha_atual">Senha Atual:</label>
            <input type="password" id="senha_atual" name="senha_atual" required><br>

            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required><br>

            <label for="confirmar_senha">Confirmar Nova Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required><br>

            <button type="submit">Alterar Senha</button>
        </form>
    </section>
    

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</htang=>