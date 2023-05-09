<?php
//controlar el acceso a la api rest
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "config.php";
include "utils.php";
$dbConn =  connect($db);


if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    //recibe por json
    $datos = file_get_contents('php://input');
    $input = json_decode($datos);

    $data = [
        'title_hangout' => $input->title_hangout,
        'city_hangout' => $input->city_hangout,
        'image_hangout' => $input->image_hangout,
        'description_hangout' => $input->description_hangout,
        'data_hangout' => $input->data_hangout,
        'hour_hangout' => $input->hour_hangout,
        'difficult_hangout' => $input->difficult_hangout,
        'id_quedada' => $input->id_quedada

    ];
    $sql = "UPDATE hangouts SET title_hangout=:title_hangout, city_hangout=:city_hangout, image_hangout=:image_hangout, description_hangout=:description_hangout, data_hangout=:data_hangout, hour_hangout=:hour_hangout, difficult_hangout=:difficult_hangout  WHERE id_hangout=:id_quedada";
    $stmt= $dbConn->prepare($sql);
    $stmt->execute($data);
    header("HTTP/1.1 200 OK");
    exit();
}




?>