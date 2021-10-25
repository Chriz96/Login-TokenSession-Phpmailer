<?php
    require 'funcs/conexion.php';
    require 'funcs/funcs.php';

    $errors = array();
    if(!empty($_POST)){

    $nombre = $mysqli->real_escape_string($_POST['nombre']); //evita sql inyection
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $passwordc = $mysqli->real_escape_string($_POST['passwordc']);
    $email = $mysqli->real_escape_string($_POST['email']);
    // $captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);

    $activo = 0; //indica que al registrar el usuario este desactivado
    $tipo_usuario = 2; //indica privilegios que tendra el usuario NO ASIGNAREMOS ADMINS
    // $secret = ''; //colocar clave secreta del captcha.

    if(isNull($nombre, $usuario, $password, $passwordc, $email))
    {
        $error[] = "Debe llenar todos los campos";
    }

    if(!isEmail($email))
    {
        $error[] = "Direccion de correo invalida";
    }

    if(!validaPassword($password, $passwordc))
    {
        $errors[] = "Las contraseñas no coinciden";
    }

    if(usuarioExiste($usuario))
    {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if(emailExiste($email))
    {
        $errors[] = "El correo electronico $email ya existe";
    }

    if(count($errors) == 0)
    {
        $pass_hash = hashPassword($password);
        $token = generateToken();

        $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);
        if($registro > 0 )
        {

            $url = 'http://'.$_SERVER["SERVER_NAME"].'/sistemaPHP/activar.php?id='.$registro.'&val='.$token;

            $asunto = 'Activar Cuenta - Sistema PHP';
            $cuerpo = "Estimado $nombre: <br /><br /> Para continuar con el proceso de registro, haga click en el siguiente enlace. <a href='$url'>Activar Cuenta</a>";

            if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
                echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado a la direccion de correo electronico: $email";
                echo "<br><a href='index.php'>Iniciar Sesion</a>";
                exit;
            }else{
                $errors[] = "Error al enviar correo";
            }

        }else{
            $errors[] = "Error al Registrar";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register - PHP</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                                    <div class="card-body">
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="nombre">Nombre</label>
                                                        <input class="form-control py-4" id="nombre" name="nombre" type="text" placeholder="Nombre" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="usuario">Usuario</label>
                                                        <input class="form-control py-4" id="usuario" name="usuario" type="text" placeholder="Usuario" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" id="inputEmailAddress" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">Password</label>
                                                        <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Enter password" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                        <input class="form-control py-4" id="inputConfirmPassword" name="passwordc" type="password" placeholder="Confirm password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0"><button type="submit" class="btn btn-primary btn-block">Create Account</button></div>
                                        </form>
                                        <?php echo resultBlock($errors);?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="index.php">Have an account? Go to login</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
