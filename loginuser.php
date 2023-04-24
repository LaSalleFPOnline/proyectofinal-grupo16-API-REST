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

/*
if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $sql = $dbConn->prepare("SELECT * FROM users where email_user=:email_user");
          $sql->bindValue(':email_user', $_GET['email_user']);
          $sql->execute();
          header("HTTP/1.1 200 OK");
          echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
          exit();
}

*/


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //recibe por json
    $data = file_get_contents('php://input');
    $input = json_decode($data);
    //$input->nombre = "victor";
    //$input->apellido = "rodriguez";
    

    $sql = $dbConn->prepare("SELECT * FROM users where email_user=:email_user");
    $sql->bindValue(':email_user', $input->email );
    //$sql->bindValue(':password_user', $input->passd );
    $passd = $input->passd;
    $sql->execute();


    if ($sql->rowCount() == 1) {

        $user = $sql->fetch(PDO::FETCH_ASSOC);

        // Obtener el ID del usuario y guardarlo en la sesión
        //$userUno = $sql->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['email'] = $input->email;

        $passwdhash = $user['password_user'];

        if (password_verify($passd, $passwdhash)) {

            header("HTTP/1.1 200 OK");
            //echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
            echo json_encode($user);

        }

        
    }


    

    //echo json_encode($input);
}


?>