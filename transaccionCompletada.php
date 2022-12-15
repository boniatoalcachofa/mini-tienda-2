<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <?php
    session_start();
    //Removes all the cart
    function removeCart() {
        //Guarda el usuario para posteriormente guardarlo en una nueva sesion
        $userLogin = $_SESSION['userLogin'];
        //Removes the session
        $_SESSION = array();
        //saves the user in the new session
        $_SESSION['userLogin'] = $userLogin;
    }
    ?>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        removeCart();
        echo "<script>alert('Operación exitosa')</script>";
        ?>
        <form action="sessionClose.php">
            <input type="submit" value="Cerrar Sesion">
        </form>
        <br>
        <form action="principal.php">
            <input type="submit" value="Menu Principal">
        </form>
        <br>
        <form action="Perfil.php">
            <input type="submit" value="Mi perfil">
        </form>
    </body>
</html>