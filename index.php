<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    
    <?php
       session_start();
    //Verifica si el formulario ha sido enviado
    function valForm(){
        return isset($_POST['enviar']);
    }
    //Function that access to the database
    function dbAccess(){
        //Informacion de la base de datos
        $dns="mysql:dbname=tiendaEroski;host:127.0.0.1";
        
        //Intenta acceder a la base de datos con PDO
        try{
            $db = new PDO($dns,"root","root");
            return $db;
        }catch(PDOException $e){
            //Ejecuta un problema al no poder acceder a la base de datos
            echo "<script>alert('Ha ocurrido un problema. Inténtelo más tarde')</script>";
        }
    }
    //Verify if the session was initialized yet
    function verifyLogin() {
     
        if(!empty($_SESSION["userLogin"])){
            header("Location: principal.php");
            die();
        }
    }
    //Verify if the user exist in the database
    function verifyUser() {
        //Access to the database
        $db = dbAccess();
        //Obtain the user from the input form
        $userName = $_POST['user'];
        //Creates the query
        $dbUserName = $db->query("SELECT * FROM usuarios WHERE usuario='$userName';");
        //verify if the user exists
        if($dbUserName->rowCount() == 0){
            $db = null;
            echo "<script>document.getElementById('usrExist').innerHTML='error'</script>";
            return false;
        }else{
            $db = null;
            return true;
        }
        
    }
    //Verify if password match with user's account
    function verifyPassw() {
        //Access to the database
        $db = dbAccess();
        //Obtain the password from the input form
        $passUser = $_POST['passUser'];
        //Obtain the user from the input form
        $userName = $_POST['user'];
        //Creates the query to obtain the username's password in database
        $dbUserName = $db->query("SELECT contrasenya FROM usuarios WHERE usuario='$userName';");
        $dbPassw = $dbUserName->fetch(PDO::FETCH_ASSOC);
        //Compare if the password in the form matches with the database's
        if($dbPassw['contrasenya']==$passUser){
             //Saves the login user and password in session's variables
            $_SESSION["userLogin"] = $_POST["user"];
            $db = null;
            return true;
        }
      
    }
    ?>
    
    
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body{
               
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
               /* background: rgb(41, 41, 41);*/
               
            }
            #formulario{
                padding: 50px;
                border: 2px solid white;
                border-radius: 12%;
                background: wheat;
            }
            #usuario{
                margin-left: 29px;
            }
        </style>
    </head>
    <body>
        
            <form action="index.php" method="post">
                <div id="formulario">
                    <p>Usuario: <input type="text" name="user" id="usuario"><span id="usrExist"></span></p>
                    <p>Contraseña: <input type="text" name="passUser"></p>
                    <input type="submit" value="Iniciar sesion" name="enviar">
                </div>
            </form>
    </body>
    <?php
    //Verify if the session is started and redirect to the page if it is
    verifyLogin();
    //start the login verification if the form has been sent
    if(valForm()){
        if(verifyUser()){
            if (verifyPassw()){
            //If matches redirect to the principal part of the store
            header("refresh: 0; principal.php");
                setcookie("Ultima conexion",date('l jS \of F Y h:i:s A'),time() + 60*60*24*7,'/');
           
            
            
            }
        }
    }
    
    ?>
</html>
