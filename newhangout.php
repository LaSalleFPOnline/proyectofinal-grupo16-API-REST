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

// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    //lo recibe por parametros
    //$input =  $_POST;
    $input = file_get_contents('php://input');
    $data = json_decode($input);
    //recibe por json
    //$data = json_decode(file_get_contents("php://input"));
    
    $sql = "INSERT INTO hangouts (title_hangout, city_hangout, image_hangout, description_hangout, data_hangout, hour_hangout, difficult_hangout) VALUES (:title_hangout, :city_hangout, :image_hangout, :description_hangout, :data_hangout, :hour_hangout, :difficult_hangout)";
    $stmt = $dbConn->prepare($sql);

    //$f = $data->data_hangout;

    //$fecha = DateTime::createFromFormat('d/m/Y', $f);
    
    $stmt->bindValue(':title_hangout', $data->title_hangout);
    $stmt->bindValue(':city_hangout', $data->city_hangout);
    $stmt->bindValue(':image_hangout', $data->image_hangout);
    $stmt->bindValue(':description_hangout', $data->description_hangout);
    $stmt->bindValue(':data_hangout', $data->data_hangout);
    $stmt->bindValue(':hour_hangout', $data->hour_hangout);
    $stmt->bindValue(':difficult_hangout', $data->difficult_hangout);


    //guardo id de user para luego crear en el targeted
    $id_use = $data->id_use;
    
    if ($stmt->execute()) {
        // Return success message
        echo json_encode(array('message' => 'Data inserted successfully'));
    } else {
        // Return error message
        echo json_encode(array('message' => 'Data insertion failed'));
    }
    


    //consulta para la ultima quedada

    $sql = $dbConn->prepare("SELECT * FROM hangouts ORDER BY id_hangout DESC LIMIT 1");
    //$sql->bindValue(':id_user', $_SESSION['id_user']);
    $sql->execute();

    $hang = $sql->fetch(PDO::FETCH_ASSOC);
    $id_hang = $hang['id_hangout'];
    header("HTTP/1.1 200 OK");
    //echo json_encode( $sql->fetch(PDO::FETCH_ASSOC) );

    //Falta pasarle el id de usuario
    // falta crear el targeted
    $sql = "INSERT INTO targeted (id_user, id_hangout) VALUES (:id_user, :id_hangout)";
    $stmt = $dbConn->prepare($sql);
    $stmt->bindValue(':id_user', $id_use);
    $stmt->bindValue(':id_hangout', $id_hang);
    $stmt->execute();
}
?>