<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); 
    exit();
}

$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT * FROM administradores WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $apelido = $_POST['apelido'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];

    $stmt = $conn->prepare("UPDATE administradores SET nome = ?, apelido = ?, telefone = ?, data_nascimento = ?, email = ?, usuario = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nome, $apelido, $telefone, $data_nascimento, $email, $usuario, $admin_id);

    if ($stmt->execute()) {
        $mensagem = "Dados atualizados com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar os dados. Tente novamente.";
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM administradores WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $admin_info = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Editar Dados do Administrador</h1>
    </header>

    <nav>
        <ul>
            <li><a href="perfil_administrador.php">Voltar à Página Administrativa</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </nav>

    <section>
        <h2>Alterar Informações</h2>
        
        <?php if (isset($mensagem)) { echo "<p>$mensagem</p>"; } ?>

        <form method="POST" action="editar_administrador.php">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($admin_info['nome']); ?>" required>

            <label for="apelido">Apelido:</label>
            <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($admin_info['apelido']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($admin_info['telefone']); ?>" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($admin_info['data_nascimento']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_info['email']); ?>" required>

            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($admin_info['usuario']); ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>
