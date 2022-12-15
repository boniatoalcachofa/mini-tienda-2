<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <?php
    include 'Producto.php';
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

        //Verifica si se ha accedido correctamente desde las credenciales
        function verifyLogin() {
            if (empty($_SESSION)){
                header("Location: sessionClose.php");
                die();
            }
        }
        //Obtain total value of cart products
        function totalValue() {
            //Go over all the products in the cart and sum the values
            foreach ($_SESSION as $key => $value) {
                //Evita que se elija el valor donde esta ubicado el usuario o el valor total de los productos 
                if($key!='userLogin'){
                    //Obtain the value of each product and saves in $totalValue
                    $totalValue += $value->price*$value->amount;
                }
            }
            return $totalValue;
        }
        //verify if the payment in the page pago.php is okay
        function verifyPayment() {
            if (empty($_POST['pago'])){
                header("Location: principal.php");
                die();
            }
        }
        //Remove products from the database
        function deleteProduct(){
            $db = dbAccess();
            foreach ($_SESSION as $key => $value) {
                //Evita que se elija el valor donde esta ubicado el usuario
                if($key!='userLogin'){
                    //Obtain the product's id
                    $id = $value->id;
                    //Call a query to obtain the product's amount
                    $q = $db->query("select cantidad from productos WHERE id=$id;");
                   
                    $q = $q->fetch(PDO::FETCH_BOTH);
                    
                    //removes the products selected by the user from the total part
                    $totalAmount = $q['cantidad']-$value->amount;
                    
                    $q = $db->query("UPDATE productos SET cantidad=$totalAmount WHERE id=$id");
                }
            }
        }
        //Remove the money from the user
        function deleteMoney() {
            $db = dbAccess();
            //Obtain the username
            $userName = $_SESSION['userLogin'];
            
            //Call a query to obtain the user's balance
            $q = $db->query("select saldo from usuarios WHERE usuario LIKE '$userName';");
            $q = $q->fetch(PDO::FETCH_BOTH);
            //Obtain the total value of the products
            $totalValue = totalValue();
            
            //Obtain the final balance of the user
            $finalBalance = $q['saldo']- $totalValue;
         
            //Sustituye el saldo del usuario por el nuevo al que se le resto lo comprado
            $q = $db->query("UPDATE usuarios SET saldo=$finalBalance WHERE usuario like '$userName';");
        }
    ?>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        verifyLogin();
        verifyPayment();
        deleteProduct();
        deleteMoney();
        header("Location: transaccionCompletada.php");
        die();
        ?>
    </body>
</html>
