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

$stmt = $conn->prepare("SELECT * FROM usuarios");
$stmt->execute();
$users = $stmt->get_result();
$stmt->close();

$stmt = $conn->prepare("SELECT consultas.*, usuarios.nome AS nome_usuario FROM consultas JOIN usuarios ON consultas.user_id = usuarios.id");
$stmt->execute();
$consultas = $stmt->get_result();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['consulta_id'])) {
    $consulta_id = $_POST['consulta_id'];
    $status = $_POST['status'];
    $comentario_cancelamento = $_POST['comentario_cancelamento'] ?? '';

    $stmt = $conn->prepare("UPDATE consultas SET status = ?, comentario_cancelamento = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $comentario_cancelamento, $consulta_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['status_update_message'] = "Status atualizado com sucesso!";
    header("Location: perfil_administrador.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Administrativa</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Página Administrativa</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </nav>

    <section>
        <h2>Informações do Administrador</h2>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($admin_info['nome']); ?></p>
        <p><strong>Apelido:</strong> <?php echo htmlspecialchars($admin_info['apelido']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($admin_info['telefone']); ?></p>
        <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($admin_info['data_nascimento']); ?></p>
        <p><strong>Idade:</strong>
            <?php
            $data_nascimento = new DateTime($admin_info['data_nascimento']);
            $data_atual = new DateTime();
            $idade = $data_atual->diff($data_nascimento)->y;
            echo htmlspecialchars($idade);
            ?>
        </p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_info['email']); ?></p>
        <p><strong>Usuário:</strong> <?php echo htmlspecialchars($admin_info['usuario']); ?></p>

        <form action="editar_administrador.php" method="GET">
            <button type="submit">Atualizar Dados</button>
        </form>
        <form action="alterar_senha_administrador.php" method="GET">
            <button type="submit">Alterar senha</button>
        </form>

        <h2>Usuários Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Apelido</th>
                    <th>Email</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['nome']); ?></td>
                        <td><?php echo htmlspecialchars($user['apelido']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                        <td>
                            <button onclick="location.href='editar_usuario_administrador.php?id=<?php echo $user['id']; ?>'" class="styled-button">Editar</button>
                            <button onclick="if(confirm('Tem certeza que deseja excluir este usuário?')) { location.href='excluir_usuario.php?id=<?php echo $user['id']; ?>'; }" class="styled-button">Excluir</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Consultas Agendadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome do Usuário</th>
                    <th>Especialidade</th>
                    <th>Data e Hora</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Observações</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($consulta = $consultas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($consulta['nome_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['especialidade']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['data_hora']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['telefone']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['email']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['observacoes']); ?></td>

                        <td>
                            <form id="statusForm<?php echo $consulta['id']; ?>" action="" method="POST">
                                <input type="hidden" name="consulta_id" value="<?php echo $consulta['id']; ?>">
                                <select name="status" id="statusSelect<?php echo $consulta['id']; ?>" onchange="handleStatusChange(this, <?php echo $consulta['id']; ?>)">
                                    <option value="Pendente" <?php echo ($consulta['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="Aceita" <?php echo ($consulta['status'] == 'Aceita') ? 'selected' : ''; ?>>Aceita</option>
                                    <option value="Cancelada" <?php echo ($consulta['status'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                </select>
                                <textarea name="comentario_cancelamento" id="comentarioCancelamento<?php echo $consulta['id']; ?>" style="display: none;" placeholder="Informe o comentario do cancelamento..."></textarea>
                            </form>
                        </td>

                        <td>
                            <button onclick="location.href='editar_consulta_administrador.php?id=<?php echo $consulta['id']; ?>'">Editar</button>
                            <button onclick="if(confirm('Tem certeza que deseja excluir esta consulta?')) { location.href='excluir_consulta_administrador.php?id=<?php echo $consulta['id']; ?>'; }">Excluir</button>
                            <button type="submit" form="statusForm<?php echo $consulta['id']; ?>">Atualizar</button> 
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <script>
            function handleStatusChange(selectElement, consultaId) {
                const status = selectElement.value;
                const comentarioCancelamento = document.getElementById('comentarioCancelamento' + consultaId);

                if (status === 'Cancelada') {
                    comentarioCancelamento.style.display = 'block'; 
                } else {
                    comentarioCancelamento.style.display = 'none'; 
                    comentarioCancelamento.value = ''; 
                }
            }

            window.onload = function() {
                <?php if (isset($_SESSION['status_update_message'])): ?>
                    alert('<?php echo $_SESSION['status_update_message']; ?>');
                    <?php unset($_SESSION['status_update_message']); ?>
                <?php endif; ?>
            };
        </script>

        <footer>
            <p>Marcações de Consultas © 2024</p>
        </footer>
</body>

</html>
