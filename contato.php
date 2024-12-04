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

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Contatos</h1>
    </header>

    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="marcar_consulta.php">Agendar consultas</a></li>
            <li><a href="perfil_utilizador.php">Voltar ao Perfil</a></li>
        </ul>
    </nav>

    <section>
        <h2>Informações de Contato</h2>
        <p><strong>Telefone:</strong> (123) 456-7890</p>
        <p><strong>Email:</strong> contato@exemplo.com</p>
        <p><strong>Morada:</strong> Avenida Europa, Viseu, Portugal</p>

        <div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13002.912261081232!2d-7.9142653!3d40.6618723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2397b478b34cd7%3A0xa5c68cc9be0e49f0!2sAvenida%20Europa%2C%20Viseu%2C%20Portugal!5e0!3m2!1sen!2spt!4v1669836763454!5m2!1sen!2spt"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>
