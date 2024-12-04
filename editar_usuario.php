<?php
session_start();
include 'config.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ''; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING);
    $apelido = filter_var(trim($_POST['apelido']), FILTER_SANITIZE_STRING);
    $telefone = filter_var(trim($_POST['telefone']), FILTER_SANITIZE_STRING);
    $data_nascimento = filter_var(trim($_POST['data_nascimento']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);

   
    $data_nascimento_formatada = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (!$data_nascimento_formatada) {
        $message = "Data de nascimento inválida.";
    } else {
        
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, apelido = ?, telefone = ?, data_nascimento = ?, email = ?, usuario = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $apelido, $telefone, $data_nascimento, $email, $usuario, $user_id);

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Dados atualizados com sucesso!</p>"; 
        } else {
            $message = "<p style='color: red;'>Erro ao atualizar os dados: " . $stmt->error . "</p>"; 
        }
        $stmt->close();
    }
} else {
    
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_info = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user_info) {
        echo "Usuário não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Editar Dados Pessoais</h1>
    </header>
    <nav>
        <ul>
            <li><a href="perfil_utilizador.php">Voltar ao perfil</a></li>
        </ul>
    </nav>
    <section>
        
        <?php if ($message): ?>
            <div><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="editar_usuario.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user_info['nome'] ?? ''); ?>" required><br>

            <label for="apelido">Apelido:</label>
            <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($user_info['apelido'] ?? ''); ?>" required><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user_info['telefone'] ?? ''); ?>" required><br>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($user_info['data_nascimento'] ?? ''); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email'] ?? ''); ?>" required><br>

            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($user_info['usuario'] ?? ''); ?>" required><br>

            <button type="submit">Atualizar</button>
        </form>

        
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>
