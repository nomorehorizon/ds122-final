<?php
require 'lib/sanitize.php';
require 'db_credentials.php';
require "authenticate.php";

$conn = mysqli_connect($servername,$username,$db_password,$dbdashboard);
if (!$conn) {
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["nova-tarefa"])) {

    $titulo = sanitize($_POST["nova-tarefa"]);
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $data_criado = date("Y-m-d H:i:s");

    $sql = "INSERT INTO $table (titulo,data_criado)
            VALUES ('$titulo', '$data_criado')";

    if(!mysqli_query($conn,$sql)){
      die("Problemas para inserir nova tarefa no BD!<br>".
           mysqli_error($conn));
    }
  }
  elseif(isset($_POST["novo-titulo-tarefa"]) && isset($_POST["id"])){
    $novo_titulo = sanitize($_POST["novo-titulo-tarefa"]);
    $id = sanitize($_POST["id"]);

    $sql = "UPDATE $table
            SET titulo='". mysqli_real_escape_string($conn, $novo_titulo) .
            "' WHERE id=" . mysqli_real_escape_string($conn, $id);

    if(!mysqli_query($conn,$sql)){
      die("Problemas para executar ação no BD!<br>".
           mysqli_error($conn));
    }
  }
}

elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["acao"]) && isset($_GET["id"])) {
    $sql = "";

    $id = sanitize($_GET['id']);
    $id = mysqli_real_escape_string($conn, $id);

    if($_GET["acao"] == "feito"){
      $sql = "UPDATE $table
              SET feito='true'
              WHERE id=" . $id;
    }
    elseif($_GET["acao"] == "desfeito"){
      $sql = "UPDATE $table
              SET feito='false'
              WHERE id=" . $id;
    }
    elseif($_GET["acao"] == "remover"){
      $sql = "DELETE FROM $table
              WHERE id=" . $id;
    }

    elseif($_GET["acao"] == "arquivar"){
      $sql = "UPDATE $table
              SET feito='arquivado'
              WHERE id=" . $id;
    }

    if ($sql != "") {
      if(!mysqli_query($conn,$sql)){
        die("Problemas para executar ação no BD!<br>".
             mysqli_error($conn));
      }
    }
  }
}

$sql = "SELECT id,titulo FROM $table WHERE feito = 'false'";
if(!($tarefas_pendentes_set = mysqli_query($conn,$sql))){
  die("Problemas para carregar tarefas do BD!<br>".
       mysqli_error($conn));
}

$sql = "SELECT id,titulo FROM $table WHERE feito = 'true'";
if(!($tarefas_concluidas_set = mysqli_query($conn,$sql))){
  die("Problemas para carregar tarefas do BD!<br>".
       mysqli_error($conn));
}

$sql = "SELECT id,titulo FROM $table WHERE feito = 'arquivado'";
if(!($tarefas_arquivadas_set = mysqli_query($conn,$sql))){
  die("Problemas para carregar tarefas do BD!<br>".
       mysqli_error($conn));
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Lista de tarefas WEB1</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/dashboard.css">
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

  <script>
    $(function(){
      $(".btn-remove-tarefa").on("click",function(){
        return confirm("Você tem certeza que deseja remover essa tarefa?");
      });
    })
  </script>
</head>

<body>
<?php if(!$login): ?>
    <?php header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");?>
    </body>
    </html>
<?php exit(); ?>
<?php endif; ?>


<div class="container">
  <div class="row">
    <div class="col-xs-offset-3 col-xs-6">
      <h1 class="page-header" style="border-bottom: 1px solid #E2AE5F;">Gerenciador de Tarefas</h1>

      <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
        <div class="form-group">
          <label class="sr-only" for="inputTarefa">Inserir nova tarefa</label>
          <input required type="text" name="nova-tarefa" class="form-control" id="inputTarefa" placeholder="Inserir nova tarefa">
        </div>
      </form>

      <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #1C1919; color: #fff; border-bottom: 1px solid #E2AE5F;">
          <h3 class="panel-title">
            <span class="glyphicon glyphicon-list" style="color: #E2AE5F"></span>
            Tarefas pendentes
          </h3>
        </div>
        <div class="panel-body">

          <?php if(mysqli_num_rows($tarefas_pendentes_set) > 0): ?>
            <?php while($tarefa = mysqli_fetch_assoc($tarefas_pendentes_set)): ?>
              <!-- INICIO TAREFA PENDENTE  -->
              <div class="alert alert-info" role="alert" style="background-color: #E2AE5F; color: #4B4141; border: 1px solid #2E2E2E;">
                <span class="tarefa">
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  <?php echo $tarefa["titulo"] ?>
                </span>
                <div class="pull-right">
                  <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=feito" ?>">
                    <button aria-label="Feito" class="btn btn-sm btn-success" type="button">
                      <span class="glyphicon glyphicon-ok"></span> Feito!
                    </button>
                  </a>

                  <a href="edita_tarefa.php?id=<?php echo $tarefa["id"]; ?>">
                    <button aria-label="Editar" class="btn btn-sm btn-info" type="button">
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>
                  </a>

                  <a class="btn-remove-tarefa" href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=remover" ?>">
                    <button aria-label="Remover" class="btn btn-sm btn-danger" type="button">
                      <span class="glyphicon glyphicon-trash"></span>
                    </button>
                  </a>

                </div>
              </div>
              <!-- FIM TAREFA PENDENTE -->
            <?php endwhile; ?>
          <?php else: ?>
            Nenhuma tarefa pendente!!!
          <?php endif; ?>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #1C1919; color: #fff; border-bottom: 1px solid #E2AE5F;">
          <h3 class="panel-title">
            <span class="glyphicon glyphicon-ok" style="color: #E2AE5F"></span>
            Tarefas concluídas
          </h3>
        </div>
        <div class="panel-body">
          <?php if(mysqli_num_rows($tarefas_concluidas_set) > 0): ?>
            <?php while($tarefa = mysqli_fetch_assoc($tarefas_concluidas_set)): ?>
              <!-- INICIO TAREFA CONCLUIDA -->
              <div class="alert alert-success" role="alert" style="background-color: #E2AE5F; color: #4B4141; border: 1px solid #2E2E2E;">
                <span class="tarefa">
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  <?php echo $tarefa["titulo"] ?>
                </span>
                <div class="pull-right">
                  <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=desfeito" ?>">
                    <button aria-label="Desfazer" class="btn btn-sm btn-warning" type="button">
                      <span class="glyphicon glyphicon-remove"></span> Desfazer
                    </button>
                  </a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=arquivar" ?>">
                    <button aria-label="Arquivar" class="btn btn-sm btn-alert" type="button">
                      <span class="glyphicon glyphicon-ok"></span> Arquivar!
                    </button>
                  </a>
                </div>
              </div>
              <!-- FIM TAREFA CONCLUIDA -->
            <?php endwhile; ?>
          <?php else: ?>
            Nenhuma tarefa concluída! :(
          <?php endif; ?>
        </div>
      </div>

      <div class="panel-group" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #1C1919; color: #FFF; border-bottom: 1px solid #E2AE5F;">
          <h3 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion"  href="#collapse1"></span>
            Tarefas arquivadas
          </h3>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
          <div class="panel-body">

            <?php if(mysqli_num_rows($tarefas_arquivadas_set) > 0): ?>
              <?php while($tarefa = mysqli_fetch_assoc($tarefas_arquivadas_set)): ?>

                <div class="alert alert-info" role="alert" style="background-color: #E2AE5F; color: #4B4141; border: 1px solid #2E2E2E;">
                  <span class="tarefa" style="color: #4B4141;">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <?php echo $tarefa["titulo"] ?>
                  </span>

                  <div class="pull-right">
                    <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=feito" ?>">
                      <button aria-label="Desarquivar" class="btn btn-sm btn-warning" type="button">
                        <span class="glyphicon glyphicon-ok"></span> Desarquivar!
                      </button>
                    </a>

                    <a href="edita_tarefa.php?id=<?php echo $tarefa["id"]; ?>">
                      <button aria-label="Editar" class="btn btn-sm btn-info" type="button">
                        <span class="glyphicon glyphicon-edit"></span>
                      </button>
                    </a>

                    <a class="btn-remove-tarefa" href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=remover" ?>">
                      <button aria-label="Remover" class="btn btn-sm btn-danger" type="button">
                        <span class="glyphicon glyphicon-trash"></span>
                      </button>
                    </a>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              Nenhuma tarefa arquivada!!!
            <?php endif; ?>
          </div>
        </div>
      </div>    
    </div>
    <a href="logout.php" style="text-decoration: none; padding: 10px; color: #4B4141; background-color: #E2AE5F; border-radius: 8px; ">Sair</a>
    <a href="wordle.php" style="text-decoration: none; padding: 10px; color: #fff; background-color: green; border-radius: 8px; ">Wordle</a>
  </div>
</div>
</body>
</html>
