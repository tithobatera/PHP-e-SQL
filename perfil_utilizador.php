<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("Usuário não encontrado.");
}

$stmt->close();
?>

<?php
$message = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_senha'], $_POST['usuario'])) {
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT); 
    $usuario = $_POST['usuario'];

    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE usuario = ?");
    $stmt->bind_param("ss", $nova_senha, $usuario);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>Senha atualizada com sucesso!</p>"; 
    } else {
        $message = "<p style='color: red;'>Erro ao atualizar a senha.</p>"; 
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Perfil do Usuário</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Logout</a></li>
            <li><a href="marcar_consulta.php">Agendar consultas</a></li>
        </ul>
    </nav>

    <section>
        <h2>Informações do Usuário</h2>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['nome']); ?></p>
        <p><strong>Apelido:</strong> <?php echo htmlspecialchars($user['apelido']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($user['telefone']); ?></p>
        <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($user['data_nascimento']); ?></p>
        <p><strong>Idade:</strong>
            <?php
            $data_nascimento = $user['data_nascimento'];
            $dataNascimento = new DateTime($data_nascimento);
            $dataAtual = new DateTime();
            $idade = $dataAtual->diff($dataNascimento)->y;
            echo htmlspecialchars($idade);
            ?>
        </p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Usuário:</strong> <?php echo htmlspecialchars($user['usuario']); ?></p>

        <?php if ($message): ?>
            <div><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="editar_usuario.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit">Atualizar Dados</button>
        </form>
        <form action="alterar_senha_usuario.php" method="GET">
            <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($user['usuario']); ?>">
            <button type="submit">Alterar senha</button>
        </form>
    </section>

    <section>
        <h2>Resumo das Consultas Agendadas</h2>
        <?php
        $stmt = $conn->prepare("SELECT c.*, u.nome AS nome_usuario 
                                FROM consultas c 
                                JOIN usuarios u ON c.user_id = u.id 
                                WHERE c.user_id = ? 
                                ORDER BY c.data_hora DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            while ($consulta = $result->fetch_assoc()) {
                echo "<div class='consulta'>";
                echo "<p><strong>Especialidade:</strong> " . htmlspecialchars($consulta['especialidade']) . "</p>";
                echo "<p><strong>Data e Hora:</strong> " . htmlspecialchars($consulta['data_hora']) . "</p>";
                echo "<p><strong>Observações:</strong> " . htmlspecialchars($consulta['observacoes']) . "</p>";
                echo "<p><strong>Status:</strong> " . htmlspecialchars($consulta['status']) . "</p>";

                if ($consulta['status'] === 'Cancelada') {
                    echo "<p><strong>Motivo do cancelamento:</strong> " . htmlspecialchars($consulta['comentario_cancelamento']) . "</p>";
                }

                echo "</div><hr>";
            }
        } else {
            echo "<p>Não há consultas agendadas.</p>";
        }

        $conn->close();
        ?>
        <button id="contactButton" onclick="document.getElementById('contato').scrollIntoView();">
            <a href="contato.php" id="contactButton">Em caso de dúvida, entre em contato</a>
        </button>
    </section>

    <footer id="contato">
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>