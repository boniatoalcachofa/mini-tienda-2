<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
       session_start();
       //Verify if the entry is from the button
       function verifyEnter(){
            if(empty($_POST)){
                header("Location: principal.php");
            }
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
        //Go over all the users in the database
        function rangeUser(){
            $db = dbAccess();
            
            //Obtain the userName
            $userName = $_SESSION["userLogin"];
            
            $q = $db->prepare("SELECT id,usuario FROM usuarios WHERE usuario NOT LIKE '$userName'");
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $q->execute();
            while ($row = $q->fetch()) {
                $id = $row["id"];
                $name = $row["usuario"];
                ?>
                <form action="ModificarUser.php" method="post">
                    <?php echo "</br><strong>$id.</strong> $name </br><input type='submit' name='$id' value='Modificar'>";?>
                </form>
                <form action="BorrarUser.php" method="post">
                    <?php echo " <input type='submit' name='$id' value='Borrar'></br>";?>
                </form>
                <?php
            }
        }
        //Modify user proprerties
        function modifyUser(){
            //obtain the selected user in the key
            foreach ($_POST as $key => $value) {
                
            }
        }

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <table>
            <tr>
                <td>
        <?php
        rangeUser();
        ?>
                </td>
            </tr>
        </table>
        </form>
    </body>
</html>
