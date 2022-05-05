<?php
  require 'db_credentials.php';

  if ($_SERVER['REQUEST_METHOD'] == "GET") {

    $conn = mysqli_connect($servername,$username,$password,$dbname);
    if (!$conn) {
      die("Problemas ao conectar com o BD!<br>".
      mysqli_connect_error());
    }

    $sql = "SELECT * FROM $table";
    if(!($tarefas_set = mysqli_query($conn,$sql))){
      die("Problemas para carregar tarefas do BD!<br>".
           mysqli_error($conn));
    }

    $data = array();
    while($tarefa = mysqli_fetch_assoc($tarefas_set)) array_push($data,$tarefa);
    header('Content-Type: application/json');
    echo json_encode($data);
  }else {
    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
    header($protocol . ' 400 Bad Request');
  }
?>
