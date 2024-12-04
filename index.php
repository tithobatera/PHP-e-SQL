<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações de Consultas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Marcações de Consultas</h1>
    </header>

    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="registro.php">Registro</a></li>
            <li><a href="login_admin.php">Login Administrativo</a></li>
        </ul>
    </nav>

    <section class="gallery">
        <div class="gallery-grid">
            <img src="imagens/image1.jpg" alt="Descrição da Imagem 1">
            <img src="imagens/image2.jpg" alt="Descrição da Imagem 2">
            <img src="imagens/image3.jpeg" alt="Descrição da Imagem 3">
            <img src="imagens/image4.jpg" alt="Descrição da Imagem 4">
            <img src="imagens/image5.png" alt="Descrição da Imagem 5">
        </div>
    </section>

    <section class="news-section">
    <nav>
        <ul>
            <li><a href=#>Noticias</a></li>
            <p style="color: white;">Fique atualizado com as principais notícias de Brasil via GLOBO!</p>


        </ul>
    </nav>
        <div class="news-box">
            <iframe src="https://www.globo.com/" class="news-iframe" title="Notícias GLOBO Brasil"></iframe>
        </div>
    </section>



    <footer>
        <p>Marcações de Consultas © 2024</p>
    </footer>
</body>

</html>