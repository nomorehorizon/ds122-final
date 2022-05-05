<?php
  require "db_functions.php";
  require "authenticate.php";

  $error = false;
  $password = $email = "";

  if (!$login && $_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
      $conn = connect_db();

      $email = mysqli_real_escape_string($conn, $_POST["email"]);
      $password = mysqli_real_escape_string($conn, $_POST["password"]);
      $password = md5($password);

      $sql = "SELECT id, name, email, password FROM $table_users
              WHERE email = '$email';";

      $result = mysqli_query($conn, $sql);
      if($result) {
        if(mysqli_num_rows($result) > 0) {
          $user = mysqli_fetch_assoc($result);

          if($user["password"] == $password) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/dashboard.php");
            exit();
          } else {
            $error_msg = "Senha Incorreta!";
            $error = true;
          }
        } else {
          $error_msg = "Usuário não encontrado!";
          $error = true;
        }
      } else {
        $error_msg = mysqli_error($conn);
        $error = true;
      }
    } else {
      $error_msg = "Por favor, preencha todos os dados.";
      $error = true;
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <title>Login Page</title>
</head>
<body>
  <?php if($login): ?>
    <?php header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/dashboard.php");?>
    </body>
    </html>
  <?php exit(); ?>
  <?php endif; ?>

  <?php if($error): ?>
    <h3 style="color:red"><?php echo $error_msg; ?></h3>
  <?php endif; ?>

  <form action="index.php" method="post">
    <h1>Login</h1>
    <label for="email">Email</label>
    <input style="color: #FFF;" type="text" name="email" value="<?php echo $email; ?>" required>

    <label for="password">Senha</label>
    <input style="color: #FFF;" type="password" name="password" value="" required>

    <input type="submit" name="submit" value="Entrar" class="submit">
    <p>Esqueceu a senha?<a href="register.php">Registrar</a></p>
  </form>
</body>
</html>