<?php
//controlar el acceso a la api rest
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "config.php";
include "utils.php";
$dbConn =  connect($db);


/*
  listar todos los posts o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id_user']))
    {
      //Mostrar un post
      
      $sql = $dbConn->prepare("SELECT * FROM hangouts
      INNER JOIN targeted ON hangouts.id_hangout = targeted.id_hangout
      WHERE id_user=:id_user");
      //$sql = $dbConn->prepare("SELECT * FROM hangouts where id_hangout=:id_hangout");
      $sql->bindValue(':id_user', $_GET['id_user']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetchAll()  );
      exit();
	}
}

//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_hangout'];
  //$statement = $dbConn->prepare("DELETE FROM hangouts where id_hangout=:id_hangout");
  $statement = $dbConn->prepare("DELETE FROM targeted where id_hangout=:id_hangout");
  $statement->bindValue(':id_hangout', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");

  $statement = $dbConn->prepare("DELETE FROM hangouts where id_hangout=:id_hangout");
  $statement->bindValue(':id_hangout', $id);
  $statement->execute();
	exit();
}
?>