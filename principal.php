<html>
    <?php
    //Obtain the product's amount
    include 'Producto.php';
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
    //Verifica si se ha accedido correctamente desde las credenciales
    function verifyLogin() {
        if (empty($_SESSION)){
            header("Location: sessionClose.php");
        }
    }
    //Go over all the food's parameters
    function rangeParameters($type) {
        //Obtain the Database
        $db = dbAccess();
        //Prepare the query to obtain different information depending on $type variable
        $q = $db->prepare("SELECT * FROM productos WHERE categoria like '$type';");
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute();
        while ($row = $q->fetch()){
            //Obtain the product's id
            $id = $row["id"];
            //calculate the amount of product aviables
            $amount = $row["cantidad"]-$_SESSION["prod".$id]->amount;
            //Show the product and his information
            echo "<tr><td>".$row["nombre"].": ".$row["precio"]." euros".". ".$amount." unidades<br>";
            //create an input to obtain the number of the product that the client want
            echo "<input type='number' name='$id' min='0' max='$amount' value='0' class='productQuantity'></td></tr>";
        }
    }
    //Creates the product in the cart if not exist and else adds the uds if exist
    function createProduct() {
        //Connect the page to the class product
        require_once 'Producto.php';
        //Obtain the Database
        $db = dbAccess();
        //Prepare the query to obtain the products
        $q = $db->prepare("SELECT * FROM productos;");
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute();
        //Go over all the database's products
        while ($row = $q->fetch()) {
            //Obtain the product's id from the database
            $productId =$row['id'];
            //Obtain the amount that the user put in the input
            $amount = $_POST[$productId];
            
                if ($amount > 0){
                    //Creates a new class 'Product' if don't exists
                    if (empty($_SESSION["prod".$productId])){
                        //Creates a new class product
                        $product = new Producto($productId, $row['nombre'], $amount, $row["categoria"], $row["precio"]);
                        //Saves the product in the cart session
                        $_SESSION["prod".$productId]=$product;
                    } else {
                        //Modify the amount in the cart session
                        $_SESSION["prod".$productId]->setAmount($_SESSION["prod".$productId]->amount + $amount);
                    }
            }
        }
        
        
    }
    
    //Shows the cart
    function cart() {
        ?>
    
        <?php
            
            foreach ($_SESSION as $key => $value) {
                //Evita que la key del login se muestre
                if($key!='userLogin'){
                    echo $value->showInfo()."<br>";
                }
            }
        
        ?>
    
            <?php
    }
    ?>
     <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            #tablas{
                display: flex;
                flex-direction: row;
                
            }
            #tablas table{
                margin-left: 100px
            }
            .productQuantity{
                width: 80px;
                margin: 10px;
            }
            #cart{
                float: right;
                
            }
        </style>
     </head>
     <body>
          <div id="cart">
                 <?php
                 verifyLogin();
                 if(valForm()){
                     createProduct();
                     header("location: principal.php");
                 }
                  cart();
                 ?>
                <form action="Pago.php">
                    <input type="submit" value="Pagar">
                 </form>
             </div> 
         
         <form action="principal.php" method="post">
             <div id="tablas">
                <table border="1px">
                    <tr><td>Comida</td></tr> 
                            <?php rangeParameters("comida")?>
                </table>

                <table border="1px">
                    <tr><td>Comida</td></tr> 
                            <?php rangeParameters("bebida")?>
                </table>

                <table border="1px">
                    <tr><td>Postre</td></tr> 
                            <?php rangeParameters("postre")?>
                </table>
                
                 
            </div>
             <input type="submit" value="Enviar" name="enviar">
             
         </form>
         <form action="sessionClose.php">
             <input type="submit" value="Cerrar sesion" name="enviar">
         </form>
         
         <form action="Perfil.php">
            <input type="submit" value="Mi perfil">
        </form>
         
         <?php 
         $db = dbAccess();
         //Obtain the username from session
         $usuario = $_SESSION["userLogin"];
         //Obtain the category of the user matching in the db
         $q = $db->query("SELECT categoria FROM usuarios WHERE usuario like '$usuario'");
         $userType = $q->fetch(PDO::FETCH_BOTH)[0];
         
         if($userType === "admin"){
         ?>
         <form action="Administrador.php" method="post">
               <input type="submit" value="Panel de administrador" name="submit">
           </form>
         <?php
         }
         ?>
     </body>
<html>
