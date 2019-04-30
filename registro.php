<?php
session_start();
require_once('clases/Usuario.php');

//si hay alguien logueado que vaya a home
if(!empty($_SESSION['email'])){
    header('location:home.php');
}

//inicializo variables
$errorEmail='';
$errorPass='';
$errorConfirmarPass='';
$errorAvatar='';
$email='';
$sinErrores = true;

if ($_POST) {
    $email= $_POST['email'];
    // echo "<pre>";
    // var_dump($_POST);
    // var_dump($_FILES);
    // echo "</pre>";
    // $avatar = $_FILES['avatar'];
    // echo $avatar['name'];
    // echo $_FILES['avatar']['name'];

    // Validando datos
    if($_POST['email']==''){
        $errorEmail='Introduce email';
        $sinErrores = false;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errorEmail = 'Email invalido';
        $sinErrores = false;
    }

    if (empty($_POST['password'])){
        $errorPass='Introduce contraseña';
        $sinErrores = false;
    }

    if (empty($_POST['confimarPass'])){
        $errorConfirmarPass='Repite contraseña';
        $sinErrores = false;
    }else if(!empty($_POST['password']) && $_POST['password']!=$_POST['confimarPass']){
        $errorConfirmarPass='las contraseñas no coinciden';
        $sinErrores = false;
    }

    //ahora procedo a subir el archivo y guardar en el json
    if($sinErrores){
        $avatar='';
        //subo el archivo
        if($_FILES['avatar']["error"]===UPLOAD_ERR_OK){
            $tipoImagen = $_FILES['avatar']['type'];
            //pregunto por le tipo del archivo
            if( $tipoImagen == 'image/png' || $tipoImagen == 'image/jpg' || $tipoImagen == 'image/jpeg' || $tipoImagen == 'image/gif'){
                $ext = pathinfo($_FILES['avatar']['name'],  PATHINFO_EXTENSION);
                //subo el archivo y lo guardo en la carpeta avatars
                $avatar = 'avatars/' . $_POST['email'] . '.' . $ext;
                move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
            }else{
                //error en tipo de archivo
                $errorAvatar = 'Seleccione una imagen con formato valido';
                $sinErrores = false;
            }
        }

        if ($sinErrores) {
            //hasheo la pass
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

            //instancio el usuario
            $usuario = new Usuario($_POST['email'], $pass, $avatar);
            $usuarioJson = json_encode($usuario);

            //guardar en el json
            file_put_contents('usuarios.json', $usuarioJson);

            //guardo en session el email del usuario
            $_SESSION['email'] = $usuario->getEmail();
            $_SESSION['avatar'] = $usuario->getAvatar();

            //ir al home del usuario
            header('location:home.php');
        }





    }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <h1>Registrate!</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="email">Email</label>
            <input id="email" type="text" name="email" value="<?php echo $email  ?>"><?php echo  $errorEmail; ?><br>
            <label for="pass">Contraseña</label>
            <input id="pass" type="password" name="password" value=""><?php echo $errorPass; ?><br>
            <label for="confimarPass">Confirmar contraseña</label>
            <input id="confimarPass" type="password" name="confimarPass" value=""><?php echo $errorConfirmarPass?><br>
            <label for="avatar">Avatar</label>
            <input id="avatar" type="file" name="avatar" value=""><?= $errorAvatar?> <br>
            <button type="submit">Enviar</button>
        </form>
    </body>




</html>
