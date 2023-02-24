<?php 


$host = "localhost";
$user = "root";
$password = "";
$db_name = "a_database";

$conn = mysqli_connect($host, $user, $password, $db_name);

if (!mysqli_connect_error()){
    
}else{
    echo 'not connected';
}




?>