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

<form action="<?php echo $script_name; ?>" method="POST" class="login-form">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
  </div>
  <button type="submit" class="btn btn-primary" name="login">Login</button>
  <a href="register.php" class="registerlink form-text">I Don't have an Account. Sign Up</a>
</form>

<style>
    .login-form{
        width:70%;
        margin: 20px auto;
        padding: 10px;
    }

    .registerlink{
        float: right;
    }

</style>