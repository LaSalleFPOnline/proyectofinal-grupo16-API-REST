<?php
//controlar el acceso a la api rest
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "config.php";
include "utils.php";
$dbConn =  connect($db);




//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_user'];
    //$statement = $dbConn->prepare("DELETE FROM hangouts where id_hangout=:id_hangout");
    $statement = $dbConn->prepare("DELETE FROM users where id_user=:id_user");
    $statement->bindValue(':id_user', $id);
    $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
?>