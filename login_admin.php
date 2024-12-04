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
        <h1>Login de Administrativos</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>

    <section>
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
