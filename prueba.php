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


if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $sql = $dbConn->prepare("SELECT * FROM users where email_user=:email_user");
          $sql->bindValue(':email_user', $_GET['email_user']);
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


if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    //recibe por json
    $datos = file_get_contents('php://input');
    $input = json_decode($datos);

    $passwd = password_hash($input->password_user, PASSWORD_DEFAULT);

    $data = [
        'name_user' => $input->name_user,
        'email_user' => $input->email_user,
        'password_user' => $passwd,
        'image_user' => $input->image_user,
        'description_user' => $input->description_user
    ];
    $sql = "UPDATE users SET name_user=:name_user, email_user=:email_user, password_user=:password_user, image_user=:image_user, description_user=:description_user  WHERE email_user=:email_user";
    $stmt= $dbConn->prepare($sql);
    $stmt->execute($data);
    header("HTTP/1.1 200 OK");
    echo json_encode("actualizado");
    exit();
}

?>