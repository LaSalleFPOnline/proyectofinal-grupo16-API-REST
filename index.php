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

// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    //lo recibe por parametros
    $input =  $_POST;

    //recibe por json
    //$data = file_get_contents('php://input');
    //$input = json_decode($data);

    //haseo password
    $passwd = password_hash($_POST['password_user'], PASSWORD_DEFAULT);


    
    $sql = "INSERT INTO users (name_user, email_user, password_user, image_user, description_user) 
    VALUES (:name_user, :email_user, :password_user, :image_user, :description_user)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute(array(":name_user"=>$_POST['name_user'],":email_user"=>$_POST['email_user'],":password_user"=>$passwd,":image_user"=>$_POST['image_user'],":description_user"=>$_POST['description_user']));
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
	$id = $_GET['id'];
  $statement = $dbConn->prepare("DELETE FROM users where id_user=:id");
  $statement->bindValue(':id', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);
    $sql = "
          UPDATE users
          SET $fields
          WHERE id_user='$postId'
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