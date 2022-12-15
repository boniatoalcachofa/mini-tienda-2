<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <?php
        include 'Producto.php';
        session_start();
        
        //Verifica si el formulario ha sido enviado
        function valForm(){
            return isset($_POST['pago']);
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

        //Verifica si se ha accedido correctamente desde las credenciales
        function verifyLogin() {
            if (empty($_SESSION)){
                header("Location: sessionClose.php");
                die();
            }
        }
        //Verify if cart is empty and get out of the page if it is
        function emptyCart() {
            if (count($_SESSION)==1){
                header("Location: principal.php");
                die();
            }
        }
        //Verify if the user have enought balance to pay; 
        function verifyPayment($balance,$totalValue) {
            if ($balance<$totalValue){
                echo "<tr><td>No hay saldo suficiente</td></tr>";
            }else{
                echo "<tr><td><input type='submit' value='Procesar pago' name='pago'></td></tr>";
            }
        }
        
        //Shows the user's balance and price
        function balanceAndPrice($totalValue){
            
            //Obtain the user in use
            $user = $_SESSION['userLogin'];
            
            //Obtain the database
            $db = dbAccess();
            //obtain the user's balance in database
            $q = $db->prepare("SELECT saldo FROM usuarios WHERE usuario like '$user';");
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $q->execute();
            $q = $q->fetch();
            $balance = $q['saldo'];
            //Shows the total price
            echo "<tr><td>Total: $totalValue euros<br></td></tr>";
            //Shows the total balance
            echo "<tr><td>Tienes ".$balance." Euros</td></tr>";
            return $balance;
        }
        
        //Go over the cart's product
        function cartOver() {
            //Stores the total value of all the products
            $totalValue;
            //Go over all the products in the cart and show the info
            foreach ($_SESSION as $key => $value) {
                //Evita que se elija el valor donde esta ubicado el usuario o el valor total de los productos 
                if($key!='userLogin' && $value!=''){
                    //Obtiene la informacion necesaria de cada producto del carrito y la guarda en una variable
                    $amount = $value->getAmount();
                    $id = $value->getId();
                    $price = $value->getPrice();
                    
                    //Obtain the database
                    $db = dbAccess();
                    //Obtiene, mediante una query, la cantidad de unidades en la base de datos del propido producto
                    $q = $db->query("SELECT cantidad FROM productos WHERE id=$id;");
                    $q = $q->fetch(PDO::FETCH_ASSOC);
                    //Guarda en la variable la cantidad maxima de productos que hay
                    $maxAmount = $q['cantidad'];
                    
                    echo "<tr><td>".$value->showInfo();
                    echo "<input type='number' name='$id' min='0' max='$maxAmount' value='$amount' class='productQuantity'></td></tr>";
                    //Obtain the value of each product and saves in $totalValue
                    $totalValue += $price*$amount;
                }
            }
            //Saves the total value of the cart in the session
          // $_SESSION['totalValue']=$totalValue;
            return $totalValue;
            
            
        }
        //Borra la cantidad de productos seleccionada
        function rmProducts() {
            echo '*';
            foreach ($_SESSION as $key => &$value) {
                //Remove the selected number of product
                if($key!='userLogin'){
                    $value->setAmount($_POST[$value->getId()]);
                    //Remove the product if his amount is 0
                    if($value->getAmount()==0){
                        
                        unset($_SESSION[$key]);
                    }
                }
            }
        }
    ?>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            .productQuantity{
                width: 50px;
                margin-left: 1%;
            }
            table{
                width: 50%;
            }
                #modI{
                    position: absolute;
                    left: 25%;
                    top: 10px;
                }
        </style>
    </head>
    <body>
        <?php
        
            if(valForm()){
                
                if($_POST['pago'] == 'Procesar pago'){
                    header("Location: transaccion.php");
                    die();
                }else{
                    rmProducts();
                    header("Location: Pago.php");
                    die();
                }
            } 
        ?>
        <form action="Pago.php" method="post">
        <div>
            
                <?php
                    emptyCart();
                    verifyLogin();
                    //Obtain the product's total value
                   $totalValue = cartOver();
                ?>
            
        </div>
            <div id="modI"><input type="submit" value="Modificar items" name="pago"></div>
        </form>
        
        <?php
        //Obtain the user's balance
        $balance = balanceAndPrice($totalValue);
        ?>
        
        <form action="transaccion.php" method="post">
            <?php
            //Verifica si hay suficiente dinero para el pago
            verifyPayment($balance, $totalValue);
            ?>
        </form>
        
        <form action="Perfil.php">
            <input type="submit" value="Mi perfil">
        </form>
        <form action="sessionClose.php">
             <input type="submit" value="Cerrar sesion" name="enviar">
         </form>
       
    </body>
</html>
