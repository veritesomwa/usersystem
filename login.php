<?php 
require 'db.php';

if (isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = md5($password);

    if (!empty($email) && !empty($password)){
        $query = "SELECT * FROM `users` WHERE email='".$email."' AND password='".$password_hash."'";
        $query_run = mysqli_query($conn, $query);

        if ($query_run){
            if(mysqli_num_rows($query_run) == 0){
                echo 'Invalid User Credentials.';
            }else{
                $row = mysqli_fetch_array($query_run);
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['phone_number'] = $row['phone_number'];
                $_SESSION['id'] = $row['id'];

                header('Location: '.$script_name);

            }
        }
        

    }else{
        echo 'All fields are required.';
    }


}

 

?>


<form action="<?php echo $script_name; ?>" method="POST">

<input type="text" name="email" placeholder="Enter Email">
<input type="password" name="password" placeholder="Enter Password">
<input type="submit" value="Login" name="login">

</form>