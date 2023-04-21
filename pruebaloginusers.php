<?php 
//controlar el acceso a la api rest
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "config.php";
include "utils.php";
$dbConn =  connect($db);


session_start();

//session_unset();
//session_destroy();



if ($_SERVER['REQUEST_METHOD'] == 'POST'){

  //recibe por json
  $data = file_get_contents('php://input');
  $input = json_decode($data);
  //$input->nombre = "victor";
  //$input->apellido = "rodriguez";

  //$email = $_POST['usernameEmail'];
  //$passd = $_POST['userPassword'];
  $_POST['usernameEmail'] = $input->nombre;
  $_POST['userPassword'] = $input->apellido;
  $email = $_POST['usernameEmail'];
  $passd = $_POST['userPassword'];

  //haseo password
  

  $sql = "SELECT * FROM users WHERE email_user = :email ";
  $stmt = $dbConn->prepare($sql);
  $stmt->bindParam(':email', $email);
  //$stmt->bindParam(':password', $password);
  $stmt->execute();

  if ($stmt->rowCount() == 1) {
      // Si el usuario existe, guardar la información en una sesión
      
      $_SESSION['email'] = $email;
      //$_SESSION['password'] = $password;
      //header('Location: dashboard.php');
      
      
      //echo "existe el usuario";

      

      // Obtener el ID del usuario y guardarlo en la sesión
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $passwdhash = $user['password_user'];
      //echo "el usuario es: ".$_SESSION['id_user'];

      if (password_verify($passd, $passwdhash)) {
          echo '¡La contraseña es válida!';
          $_SESSION['id_user'] = $user['id_user'];

          // cookies
          $idUsuario = $_SESSION['id_user'];
          setcookie("usuario", $idUsuario, time()+600);

          //$id_usuario = 34;
          //echo $_SESSION['id_user'];
          //echo $_SESSION['email'];
          //header('Location: http://localhost/apibici/getallusers.php');
          //header('Location: http://localhost:4200/perfil/34');

          $sql = $dbConn->prepare("SELECT * FROM users where id_user=:id_user");
          $sql->bindValue(':id_user', $_SESSION['id_user']);
          $sql->execute();
          header("HTTP/1.1 200 OK");
          echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
          exit();
      } else {
          //echo 'La contraseña no es válida.';
      }
      //echo "<br>".$passwdhash;
      //echo "<br>".$passd;

  

  } else {
      // Si el usuario no existe, mostrar un mensaje de error
      echo "El usuario o la contraseña son incorrectos.";
  }

  
}

/*
  listar todos los posts o solo uno
 */
if( isset( $_COOKIE["usuario"]) ){

  echo "usuario con cookie";
  echo $_COOKIE["usuario"];

}else if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_SESSION['id_user'])){
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM users where id_user=:id_user");
      $sql->bindValue(':id_user', $_SESSION['id_user']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();

    }else if (isset($_GET['name_user']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM users where name_user=:name_user");
      $sql->bindValue(':name_user', $_GET['name_user']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetchAll()  );
      exit();
    }
    if (isset($_GET['id_user']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM users where id_user=:id_user");
      $sql->bindValue(':id_user', $_GET['id_user']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM users");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}

$dbConn = null;
?>