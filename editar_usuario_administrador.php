<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID do usuário não fornecido.";
    exit();
}

$user_id = (int) $_GET['id']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING);
    $apelido = filter_var(trim($_POST['apelido']), FILTER_SANITIZE_STRING);
    $telefone = filter_var(trim($_POST['telefone']), FILTER_SANITIZE_STRING);
    $data_nascimento = filter_var(trim($_POST['data_nascimento']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);

    $data_nascimento_formatada = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (!$data_nascimento_formatada) {
        echo "Data de nascimento inválida.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, apelido = ?, telefone = ?, data_nascimento = ?, email = ?, usuario = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nome, $apelido, $telefone, $data_nascimento, $email, $usuario, $user_id);

    if ($stmt->execute()) {
        header("Location: editar_usuario_administrador.php?id=$user_id&success=1"); 
        exit();
    } else {
        echo "Erro ao atualizar os dados: " . $stmt->error;
    }

    $stmt->close();
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
    <title>Editar Usuário ADMIN</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                alert("Dados atualizados com sucesso!");
            }
        };
    </script>
</head>

<body>
    <header>
        <h1>Editar Usuário ADMIN</h1>
    </header>

    <nav>
        <ul>
            <li><a href="perfil_administrador.php">Meu perfil</a></li>
        </ul>
    </nav>

    <section>
        <form action="editar_usuario_administrador.php?id=<?php echo $user_id; ?>" method="POST">
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
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>