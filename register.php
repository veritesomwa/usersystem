<?php 
require('server.php');
require('db.php');

if (isset($_SESSION['id']) && !empty($_SESSION['id'])){
    header('Location: index.php');
}else{

    // Registration Begins here


}


?>

<form action="register.php" method="POST">
    <input type="text" name="firstname" placeholder="Firstname">
    <input type="text" name="lastname" placeholder="Lastname">
    <input type="text" name="email" placeholder="Enter Email">
    <input type="text" name="password1" placeholder="Password">
    <input type="text" name="password2" placeholder="Confirm Password">
    <input type="number" name="phone_number" placeholder="Enter Phone Number">
</form>