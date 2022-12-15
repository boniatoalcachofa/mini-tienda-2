<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
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
        
        //Shows the user's data to modify
        function userData() {
            $db = dbAccess();
            //Obtain the selected user's id
            $id = array_keys($_POST)[0];
            //Obtain the user's info from the database
            $q = $db->prepare("SELECT id,usuario,contrasenya,saldo from usuarios WHERE id='$id';");
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $q->execute();
            $q = $q->fetch();
            
            //saves the info into variables
            $userName = $q['usuario'];
            $passWord = $q['contrasenya'];
            $balamce = $q['saldo'];
            
            echo "<input type='text' value='$userName' maxlength='16' name='usu'>";
            echo "<input type='text' value='$passWord' maxlength='16' name='contra'>";
            echo "<input type='text' value='$balamce' maxlength='16' name='saldo'>";
            
        }
        
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        userData();
        ?>
    </body>
</html>
