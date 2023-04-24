<?php
//controlar el acceso a la api rest
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

// Conexión a la base de datos con PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biciquedadasbbdd";




try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "conectado";
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
session_start();
// Verificar si el usuario existe en la tabla users
//$email = $_POST['email'];
//$password = $_POST['password'];
/*
if(session_status()==2){
    echo "activo";
    //Mostrar un post
    $sql = $conn->prepare("SELECT * FROM users where id_user=:id_user");
    $sql->bindValue(':id_user', $_SESSION['id_user']);
    $sql->execute();
    header("HTTP/1.1 200 OK");
    echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
    exit();
}
*/

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    
      //Mostrar un post
      $sql = $conn->prepare("SELECT * FROM users where id_user=:id_user");
      $sql->bindValue(':id_user', $_SESSION['id_user']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //recibe por json
    $data = file_get_contents('php://input');
    $input = json_decode($data);
    //$input->nombre = "victor";
    //$input->apellido = "rodriguez";
    

    $sql = $dbConn->prepare("SELECT * FROM users where email_user=:email_user AND password_user=:password_user");
    $sql->bindValue(':email_user', $input->email );
    $sql->bindValue(':password_user', $input->passd );
    $sql->execute();


    if ($sql->rowCount() == 1) {

        // Obtener el ID del usuario y guardarlo en la sesión
        //$userUno = $sql->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['email'] = $input->email;

        header("HTTP/1.1 200 OK");
        echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
    }


    

    //echo json_encode($input);
}


/*
if ($_SERVER['REQUEST_METHOD'] == 'POST'){


    $email = $_POST['usernameEmail'];
    $passd = $_POST['userPassword'];

    //haseo password
    

    $sql = "SELECT * FROM users WHERE email_user = :email ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Si el usuario existe, guardar la información en una sesión
        
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        //header('Location: dashboard.php');
        
        
        echo "existe el usuario";

        

        // Obtener el ID del usuario y guardarlo en la sesión
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id_user'] = $user['id_user'];
        $passwdhash = $user['password_user'];
        echo "el usuario es: ".$_SESSION['id_user'];

        if (password_verify($passd, $passwdhash)) {
            echo '¡La contraseña es válida!';
            //header('Location: http://localhost:4200/perfil');
        } else {
            echo 'La contraseña no es válida.';
        }
        //echo "<br>".$passwdhash;
        //echo "<br>".$passd;

    

    } else {
        // Si el usuario no existe, mostrar un mensaje de error
        echo "El usuario o la contraseña son incorrectos.";
    }

    $conn = null;
}
*/

?>
