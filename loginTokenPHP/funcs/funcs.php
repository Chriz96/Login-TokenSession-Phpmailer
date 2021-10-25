<?php
    use PHPMailer\PHPMailer\PHPMailer;
    function isNull($nombre, $user, $pass, $pass_con, $email){
        if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($email)) < 1){
            return true;
        }else{
            return false;
        }
    }

    function isEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }

    function validaPassword($var1, $var2)
    {
        if(strcmp($var1, $var2)!== 0){
            return false;
        }else{
            return true;
        }
    }

    function minMax($min, $max, $valor){
        if(strlen(trim($valor)) < $min){
            return true;
        }else if(strlen(trim($valor)) >$max){
            return true;
        }else{
            return false;
        }
    }
    
    function usuarioExiste($usuario)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1"); // ?  indica que va un valor alli
        $stmt->bind_param("s", $usuario); // aqui indicamos que tipo de valor va "s" string "i" int
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if($num > 0){
            return true;
        }else{
            return false;
        }

    }

    function emailExiste($email)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1"); // ?  indica que va un valor alli
        $stmt->bind_param("s", $email); // aqui indicamos que tipo de valor va "s" string "i" int
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if($num > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    function generateToken(){
        $gen = md5(uniqid(mt_rand(), false));
        return $gen;
    }

    function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario){

        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssisi', $usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);

        if($stmt->execute()){
            return $mysqli->insert_id;
        }else{
            return 0;
        }
    }

    function enviarEmail($email, $nombre, $asunto, $cuerpo){
        

        require("PHPMailer-master/src/PHPMailer.php");
        require("PHPMailer-master/src/Exception.php");
        require("PHPMailer-master/src/SMTP.php");
        // $mail = new PHPMailer();
       
        $mail = new PHPMailer(); 
        // $mail->SMTPDebug = 2; Usar para verificar errores
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";

        // $mail->CharSet="UTF-8";
      
        // $mail->Host = 'smtp.gmail.com';
        // $mail->SMTPSecure = 'tipo de seguridad';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;

        $mail->Username = "";
        $mail->Password = "";
        

        // $mail->SetFrom('miemail@dominio.com', 'Sistema con PHP');
        // $mail->AddAddress($email, $nombre);
        $mail->SetFrom('', 'Login - PHP');
        $mail->AddAddress($email, $nombre);

        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        $mail->IsHTML(true);

        if($mail->Send())
        return true;
        else
        return false;
    }

    function resultBlock($errors){
        if(count($errors) > 0)
        {
            echo "<div id='error' class='alert alert-danger' role='alert'>
            <a href='#' onclick=\"showHide('error');\">[X]</a><ul>";
            foreach($errors as $error)
            {
                echo "<li>".$error."</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }

    function validaIdToken($id, $token){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE id = ? AND token = ? LIMIT 1");
        $stmt->bind_param("is", $id, $token);
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;

        if($rows > 0){

            $stmt->bind_result($activacion);
            $stmt->fetch();

            if($activacion == 1){
                $msg = "La cuenta ya se activo anteriormente.";
            }else{
                if(activarUsuario($id)){
                    $msg = 'Cuenta activada.';
                }else{
                    $msg = 'Error al Activar Cuenta';
                }
            }
        }else{
            $msg = 'No existe el registro para activar.';
        }

        return $msg;
    }

    function activarUsuario($id){
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE usuarios SET activacion=1 WHERE id = ?");
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    function isNullLogin($usuario, $password){
        if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1 )
        {
            return true;
        }else{
            return false;
        }
    }

    function login($usuario, $password)
    {
        global $mysqli;
        
        $stmt = $mysqli->prepare("SELECT id, id_tipo, password, nombre FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;

        if($rows > 0){

            if(isActivo($usuario)){

                $stmt->bind_result($id, $id_tipo, $passwd, $nombre);
                $stmt->fetch();
                
                $validaPass = password_verify($password, $passwd);

                if($validaPass){
                    lastSession($id);
                    $_SESSION['id_usuario'] = $id;
                    $_SESSION['tipo_usuario'] = $id_tipo;
                    $_SESSION['nombre'] = $nombre;
                    header("location: principal.php");
                }else{
                    $errors = "La password no coincide";
                }
            }else{
                $errors = 'El usuario no esta activo';
            }
        }else{
            $errors = 'El nombre de usuario no existe';
        }
        return $errors;
    }


    function lastSession($id)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE usuarios SET last_session=NOW(), token_password='', password_request=1 WHERE id= ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->close();
    }

    function isActivo($usuario){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $stmt->bind_result($activacion);
        $stmt->fetch();

        if($activacion == 1)
        {
            return true;
        }else{
            return false;
        }

    }
    //$campo: que requerimos, $campoWhere: es el que usaremos para filtrar y $valor: valor del campo
    function getValor($campo, $campoWhere, $valor)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT $campo FROM usuarios WHERE $campoWhere = ? LIMIT 1");
        $stmt->bind_param('s', $valor);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0 )
        {
            $stmt->bind_result($resultado);
            $stmt->fetch();
            return $resultado;
        }
        else
        {
            $errors = 'El correo no existe';
           
        }
        return $errors;
    }

    function generateTokenPass($user_id)
    {
        global $mysqli;
        
        $token = generateToken();
        //update setea el token en bd y cambia el password request a 1 para saber que el usuario solicito el reseteo.
        $stmt = $mysqli->prepare("UPDATE usuarios SET token_password = ?, password_request = 1 WHERE id = ?");
        $stmt->bind_param('ss', $token, $user_id);
        $stmt->execute();
        $stmt->close();
        
        return $token;
    }

    function verificaTokenPass($user_id, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE id = ? AND token_password = ? AND password_request = 1 LIMIT 1");
        $stmt->bind_param('is', $user_id, $token );
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($activacion);
            $stmt->fetch();
            if($activacion == 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    function cambiaPassword($password, $user_id, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE usuarios SET password = ?, token_password = '', password_request = 0 WHERE id = ? AND token_password = ?");
        $stmt->bind_param('sis', $password, $user_id, $token);

        if($stmt->execute())
        {
            return true;
        }else{
            return false;
        }
    }
?>