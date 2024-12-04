<?php
session_start();
include 'config.php'; // Inclua o arquivo de configuração para acessar o banco de dados

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o ID do usuário foi passado pela URL
if (!isset($_GET['id'])) {
    echo "ID do usuário não fornecido.";
    exit();
}

$user_id = (int) $_GET['id']; // Captura o ID do usuário e o converte para inteiro

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura e sanitiza os dados do formulário
    $nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING);
    $apelido = filter_var(trim($_POST['apelido']), FILTER_SANITIZE_STRING);
    $telefone = filter_var(trim($_POST['telefone']), FILTER_SANITIZE_STRING);
    $data_nascimento = filter_var(trim($_POST['data_nascimento']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);

    // Valida se a data de nascimento é válida
    $data_nascimento_formatada = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (!$data_nascimento_formatada || $data_nascimento_formatada->format('Y-m-d') !== $data_nascimento) {
        echo "Data de nascimento inválida.";
        exit();
    }

    // Atualiza os dados do usuário no banco
    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, apelido = ?, telefone = ?, data_nascimento = ?, email = ?, usuario = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nome, $apelido, $telefone, $data_nascimento, $email, $usuario, $user_id);

    if ($stmt->execute()) {
        header("Location: perfil_utilizador.php?success=1"); // Redireciona para a página do perfil do administrador após a atualização
        exit();
    } else {
        echo "Erro ao atualizar os dados: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    // Busca os dados do usuário para preencher o formulário
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
        <h1>Editar Usuário</h1>
    </header>

    <section>
        <form action="editar_usuario.php?id=<?php echo $user_id; ?>" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user_info['nome']); ?>" required><br>

            <label for="apelido">Apelido:</label>
            <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($user_info['apelido']); ?>" required><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user_info['telefone']); ?>" required><br>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($user_info['data_nascimento']); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required><br>

            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($user_info['usuario']); ?>" required><br>

            <button type="submit">Atualizar</button>
        </form>

        <?php
        // Exibe mensagem de sucesso se redirecionado
        if (isset($_GET['success'])) {
            echo "<p style='color: green;'>Dados atualizados com sucesso!</p>";
        }
        ?>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</html>
