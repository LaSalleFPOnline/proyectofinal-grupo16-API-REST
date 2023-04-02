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
    if (isset($_GET['id_hangout']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM hangouts where id_hangout=:id_hangout");
      $sql->bindValue(':id_hangout', $_GET['id_hangout']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM hangouts");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}
// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    //lo recibe por parametros
    $input =  $_POST;

    //recibe por json
    //$data = file_get_contents('php://input');
    //$input = json_decode($data);
    
    $sql = "INSERT INTO hangouts (title_hangout, city_hangout, image_hangout, description_hangout) 
    VALUES (:title_hangout, :city_hangout, :image_hangout, :description_hangout)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      //$input['id'] = $postId;
      header("HTTP/1.1 200 OK");
      //echo json_encode($input);
      header('Location: http://localhost:4200/registro-usuario');
      exit();
	 }
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_hangout'];
  $statement = $dbConn->prepare("DELETE FROM users where id_hangout=:id_hangout");
  $statement->bindValue(':id_hangout', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id_hangout'];
    $fields = getParams($input);
    $sql = "
          UPDATE hangouts
          SET $fields
          WHERE id_hangout='$postId'
           ";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>