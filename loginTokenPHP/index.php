<?php

    // require "conection.php";
    require 'funcs/conexion.php';
    require 'funcs/funcs.php';

    session_start();

    $errors = array();
    
    if(!empty($_POST))
    {
        $usuario = $mysqli->real_escape_string($_POST['usuario']);
        $password = $mysqli->real_escape_string($_POST['password']);

        if(isNullLogin($usuario, $password)){
            $errors[] = "Debe llenar todos los campos";
        }
        $errors[] = login($usuario, $password);
    }


    // if($_POST){

    //     $usuario = $_POST['usuario'];
    //     $password = $_POST['password'];

    //     $sql = "SELECT id, password, nombre, id_tipo FROM usuarios WHERE usuario='$usuario'";

    //     $resultado = $mysqli->query($sql);
    //     $num = $resultado->num_rows;

    //     if($num>0){

    //         $row = $resultado->fetch_assoc();
    //         $password_bd = $row['password'];

    //         $pass_c = password_hash($password, PASSWORD_DEFAULT);
            
    //         if($password_bd == $pass_c){

    //             $_SESSION['nombre'] = $row['nombre'];
    //             $_SESSION['id_tipo'] = $row['id_tipo'];
    //             $_SESSION['id'] = $row['id'];

    //             header("Location: principal.php");
                
    //         }else{
    //             echo "La contraseña no coincide";
    //         }

    //     }else{
    //     echo "NO EXISTE USUARIO";
    //     }
    // }



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Panel</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" id="inputEmailAddress" name="usuario" type="text" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Enter password" />
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                                    <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="restorePass.php">Forgot Password?</a>
                                                <button type="submit" class="btn btn-primary" >Login</button>
                                            </div>
                                        </form>
                                        <?php echo resultBlock($errors); ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="registro.php">Need an account? Sign up!</a></div>
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
