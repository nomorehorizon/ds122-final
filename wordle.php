<?php 
  require "authenticate.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Prova</title>
  <link rel="stylesheet" href="css/wordle.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet">
</head>
<body>
  <?php if(!$login): ?>
    <?php header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/login.php");?>
    </body>
    </html>
  <?php exit(); ?>
  <?php endif; ?>

  <h1>Wordle</h1>
  <input type="text" id="chute" placeholder="Palavra com 5 letras">
  <button onclick="start()">Enviar!</button>

  <div id="board"></div>

  <a href="dashboard.php" style="padding: 10px; color: #4B4141; text-decoration: none; background-color: #E2AE5F; border-radius: 8px; margin-bottom: 50px; ">Voltar</a>

  <div class="feito">Feito por:
    Lucas Machado
  </div>

  <script src="wordle.js"></script>
</body>
</html>