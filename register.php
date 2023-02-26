<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<?php 
require('server.php');
require('db.php');

if (isset($_SESSION['id']) && !empty($_SESSION['id'])){
    header('Location: index.php');
}else{

    // Registration Begins here
    if (isset($_POST['register'])){

        $firstname = $_POST['firstname'];
        $firstnameHelp = "";

        $lastname = $_POST['lastname'];
        $lastnameHelp = "";

        $email = $_POST['email'];
        $emailHelp = "";

        $password = $_POST['password'];
        $passwordHelp = "";

        $password2 = $_POST['password2'];
        $password2Help = "";

        $alert = "";
        $check = [];

        $phone = $_POST['phone'];

        function HasNoEmptyField(...$params){
            $check = 1;
            for ($i=0; $i <= count($params) - 1; $i++) { 
                if ($params[$i] == ""){
                    $check =  0;
                    break;
                }
            }

            return $check;
        }

        function CheckStringLength($string, $len){
            if(strlen($string) >= $len){
                return 1;
            }else{
                return 0;
            }
        }

        function passwordMatch($pass, $pass2){
            if($pass == $pass2){
                return 1;
            }else{
                return 0;
            }
        }

        function emailValidation($email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return 1;
            }else{
                return 0;
            }
        }

        function primaryKeyDontExists($db, $key){
            $query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($db, $key)."'";
            if ($result = mysqli_query($db, $query)){
                if (mysqli_num_rows($result) == 0){
                    return 1;
                }else{
                    return 0;
                }
            }
        }

        function HasInterger($string){
            $numbers = "1234567890";
            $check = 0;
            for($i = 0; $i <= strlen($numbers) - 1; $i++){
                if(str_contains($string, $i)){
                    $check = 1;
                    break;
                }
            }

            return $check;
        }

        function Haslowers($string){
            $abc = "abcdefghijklmnopqrstuvwxyz";
            $check = 0;
            for($i = 0; $i <= strlen($abc) - 1; $i++){
                if(str_contains($string, $abc[$i])){
                    $check = 1;
                    break;
                }
            }

            return $check;
        }

        function Hasuppers($string){
            $abc = strtoupper("abcdefghijklmnopqrstuvwxyz");
            $check = 0;
            for($i = 0; $i <= strlen($abc) - 1; $i++){
                if(str_contains($string, $abc[$i])){
                    $check = 1;
                    break;
                }
            }

            return $check;
        }
        


        if( passwordMatch($password, $password2) == 0){
            $password2Help = "Passwords do not Match";
            $passwordHelp = "Passwords do not Match";
            array_push($check, 0);
        }else{
            array_push($check, 1);
            $password2Help = "";
            $passwordHelp = "";
        }



        if (!emailValidation($email)){
            $emailHelp = "Invalid email format: example <strong>username@domain.com</strong>";
            array_push($check, 0);
        }elseif (primaryKeyDontExists($conn, $email) == 0){
            $emailHelp = "There's already a user with this email.";
            array_push($check, 0);
        }else{
            $emailHelp = "";
            array_push($check, 1);
        }




        if(!CheckStringLength($password, 8)){
            $passwordHelp = "Password must be 8 Character or more";
            array_push($check, 0);
        }elseif (Haslowers($password) === 0){
            $passwordHelp = "Password must contain both lower and Upper Case a-z";
            array_push($check, 0);
        }elseif (Hasuppers($password) === 0){
            $passwordHelp = "Password must contain both lower and Upper Case A-Z";
            array_push($check, 0);
        }elseif (HasInterger($password) === 0){
            $passwordHelp = "Password must contain a number 0-9";
            array_push($check, 0);
        }else{
            $passwordHelp = "";
            array_push($check, 1);
        }

        function alert($str, $color){
            return '<div class="alert alert-'.$color.'">'.$str.'</div>';
        }
        
        
        if (!HasNoEmptyField($firstname, $lastname, $email, $password, $password2, $phone)){
            $alert = alert("All fields are required.", "danger");
            array_push($check, 0);
        }else{
            $alert = "";
            array_push($check, 1);
        }

        $haserror = 0;
        foreach($check as $c){
            if ($c == 0){
                $haserror = 1;
            }
        }

        if ($haserror == 0){
            // Register

            $query = "Insert INTO `users` VALUES ('', '".mysqli_real_escape_string($conn, $firstname)."', '".mysqli_real_escape_string($conn, $lastname)."', '".mysqli_real_escape_string($conn, $email)."', '".mysqli_real_escape_string($conn, md5($password))."', '".mysqli_real_escape_string($conn, $phone)."')";
            if($result = mysqli_query($conn, $query)){
                $loginquery = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($conn, $email)."'";
                $query_run = mysqli_query($conn, $loginquery);

                $row = mysqli_fetch_assoc($query_run);

                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['phone_number'] = $row['phone_number'];

                header('Location: index.php');
            }else{
                echo 'Query unsuccessful';
            }

        }
        


        

    }


}


?>


<form action="register.php" method="POST" class="reg-form">
  <div class="mb-3">
    <label for="firstname" class="form-label">First Name</label>
    <input type="text" class="form-control" id="firstname" name="firstname" aria-describedby="firstnameHelp" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']?>">
    <div id="firstnameHelp" class="form-text text-danger"><?php if(isset($_POST['register'])) echo $firstnameHelp?></div>
  </div>
  <div class="mb-3">
    <label for="lastname" class="form-label">Last Name</label>
    <input type="text" class="form-control" id="lastname" name="lastname" aria-describedby="lastnameHelp" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']?>">
    <div id="lastnameHelp" class="form-text text-danger"><?php if(isset($_POST['register'])) echo $lastnameHelp?></div>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>">
    <div id="emailHelp" class="form-text text-danger"><?php if(isset($_POST['register'])) echo $emailHelp;?></div>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']?>">
    <div id="passwordHelp" class="form-text text-danger"><?php if(isset($_POST['register'])) echo $passwordHelp?></div>
  </div>
  </div>
  <div class="mb-3">
    <label for="password2" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" name="password2" id="password2" value="<?php if(isset($_POST['password2'])) echo $_POST['password2']?>">
    <div id="password2Help" class="form-text text-danger"><?php if(isset($_POST['register'])) echo $password2Help?></div>
  </div>
  <div class="form-outline mb-3" style="width: 100%; max-width: 22rem">
    <input type="text" id="phone"  name="phone" class="form-control" data-mdb-input-mask="+48 999-999-999" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']?>"/>
    <label class="form-label" for="phone">Phone number with country code</label>
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
    <label class="form-check-label" for="remember_me">Remember Me</label>
  </div>
  <button type="submit" class="btn btn-primary" name="register">Register</button>
  <a href="index.php" class="loginlink form-text">I have an Account. Login Instead</a>
  <br><br>
  <div><?php if(isset($_POST['register'])) echo $alert;?></div>
</form>


<style>
    .reg-form{
        width:70%;
        margin: 20px auto;
        padding: 10px;
    }

    .loginlink{
        float: right;
    }

</style>


<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
</script>
</body>
</html>

