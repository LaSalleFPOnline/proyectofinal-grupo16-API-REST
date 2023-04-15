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

// Verificar si el usuario existe en la tabla users
//$email = $_POST['email'];
//$password = $_POST['password'];
if (($_SERVER['REQUEST_METHOD'] == 'POST')){


    $email = $_POST['usernameEmail'];
    $passd = $_POST['userPassword'];

    //haseo password
    

    $sql = "SELECT * FROM users WHERE email_user = :email ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    //$stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Si el usuario existe, guardar la información en una sesión
        session_start();
        $_SESSION['email'] = $email;
        //$_SESSION['password'] = $password;
        //header('Location: dashboard.php');
        
        
        echo "existe el usuario";

        

        // Obtener el ID del usuario y guardarlo en la sesión
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id_user'] = $user['id_user'];
        $passwdhash = $user['password_user'];
        echo "el usuario es: ".$_SESSION['id_user'];

        if (password_verify($passd, $passwdhash)) {
            echo '¡La contraseña es válida!';
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
?>
