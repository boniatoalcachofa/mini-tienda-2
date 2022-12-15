<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <?php
    session_start();
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
        
    //Obtiene la informacion de usuario
    function userInfo() {
        $db = dbAccess();
        
        //Obtain the username of the user
        $userName = $_SESSION['userLogin'];
        
        $q = $db->query("SELECT * FROM usuarios WHERE usuario LIKE '$userName';");
        $q = $q->fetch(PDO::FETCH_ASSOC);
        //Obtain the info from usuarios
        $userName = $q['usuario'];
        $id = $q['id'];
        $balance = $q['saldo'];
        $cat = $q['categoria'];
        
        echo "<tr><td>Nombre de usuario: $userName</td></tr>";
        echo "<tr><td>Id: $id</td></tr>";
        echo "<tr><td>Dinero: $balance</td></tr>";
        echo "<tr><td>Tipo de cuenta: $cat</td></tr>";
    }
    ?>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <table>
            <tr><td>Información de usuario:</td></tr>
            <?php userInfo();?>
            
        </table>
        <form action="sessionClose.php">
            <input type="submit" value="Cerrar Sesion">
        </form>
        
        <form action="principal.php">
            <input type="submit" value="Menu Principal">
        </form>
    </body>
</html>
