<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Registro de Usuários</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>

    <section>
        <form action="processa_registro.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>

            <label for="apelido">Apelido:</label>
            <input type="text" id="apelido" name="apelido" required><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required><br>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br>

            <label for="confirmar_senha">Confirmação de Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required><br>

            <button type="submit">Cadastrar</button>
            <button type="reset">Limpar Informações</button>
        </form>
    </section>

    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>
</html>
