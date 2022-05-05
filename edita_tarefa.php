<?php
require 'lib/sanitize.php';
require 'db_credentials.php';

$conn = mysqli_connect($servername,$username,$db_password,$dbdashboard);
if (!$conn) {
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["id"])) {

    $id = sanitize($_GET['id']);
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT id,titulo FROM $table WHERE id = ". $id;

    if(!($tarefa = mysqli_query($conn,$sql))){
      die("Problemas para carregar tarefas do BD!<br>".
           mysqli_error($conn));
    }
  }
}

mysqli_close($conn);

if (mysqli_num_rows($tarefa) != 1) {
  die("Id de tarefa incorreto.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Lista de tarefas WEB1</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/bootstrap.css">
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <style media="screen">
    .alert a:hover{
      text-decoration: none;
    }
    .alert .tarefa {
      font-size: 1.3em;
    }

    h3.panel-title{
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-xs-offset-3 col-xs-6">
      <h1 class="page-header">Editar tarefa</h1>

      <form action="dashboard.php" method="POST">
        <div class="form-group">
          <?php $tarefa = mysqli_fetch_assoc($tarefa); ?>
          <input type="hidden" name="id" value="<?php echo $tarefa["id"] ?>">
          <label class="sr-only" for="inputTarefa">Editar tarefa</label>
          <input required type="text" name="novo-titulo-tarefa" class="form-control" id="inputTarefa" value="<?php echo $tarefa["titulo"] ?>">
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
