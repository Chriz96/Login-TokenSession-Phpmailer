<h1><img src="https://images.emojiterra.com/google/noto-emoji/unicode-13.1/128px/1f1ea-1f1f8.png" alt="Spain flag" width="40" height="40" /> [PHP] CRUD - Login - Restaurar Password</h1>
<h3> Proyecto que solo permite crear cuenta, activar cuenta, restaurar contraseña mediante el uso de tokens y envio de correos con el uso de phpmailer. Las tablas y gráficos que aparecen son solo ejemplos, en el apartado addons -> tables en el menú de la izquierda se muestra una tabla con los usuarios creados. </h3>

<h2> Pre-requisitos 📋 </h2>
<ul>
  <li>
Importar la base de datos adjunta llamada "login.sql" (Se encuentra en el directorio DataBase). Usada para guardar y verificar los usuarios creados, las contraseñas estan encriptadas bajo password_hash .
  </li>
  <li>
Para crear una cuenta se debe editar parte del archivo funcs.php ubicado en ../funcs/:
 </li>
  <ul>
    <li>
  Dirigirse a la linea 119: Para indicar donde se encuentra alojado el servidor de correo. En caso de ser gmail: $mail->Host = "smtp.gmail.com";
    </li>
     <li>
  Dirigirse a la linea 120: Para indicar el puerto de conexión del servidor SMTP. En caso de ser gmail: $mail->Port = 587;
    </li>
     <li>
  En la linea 122: indicar el correo electrónico propio que usaran como mensajero para enviar links de activación de cuentas y reinicio de contraseñas.
    </li>
    <li>
  En la linea 123: Indicar la contraseña del correo electrónico colocado en la linea anterior.
    </li>
  </ul>
  <li>
    En caso de tener algun problema que no se envian los correos electrónicos al crear cuenta o restaurar contraseña, deben dirigirse a su usuario de gmail y entrar en "Administra tu Cuenta de Google" una vez alli dirigirse a la pestaña  "Seguridad" y hacer lo siguiente: 
  </li>
  <ul>
    <li>
      Apartado "Verificacion en dos pasos" desactivar.
    </li>
        <li>
      Apartado "Acceso de apps menos seguras" no.
    </li>
    <li>
      Una vez hecho estos cambios deberia funcionar correctamente el envio de correos.
    </li>
  </ul>
</ul>

<h2> Construido con 🛠️ </h2>

 * **PHP** version 7.3.27.
 * **PHPMailer** version 6.3.0.
 * **Bootstrap** version v4.5.3.
 * **Xampp** version 7.3.27.
 
 <h2> Contribuyendo 🖇️ </h2>

* A todo aquel que descargue el proyecto y lo utilice como por ejemplo para ver alguna funcionalidad, si encuentra algun problema o sugerencia sea libre de comentarla.
