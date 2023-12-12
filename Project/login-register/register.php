<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management library</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="/images/234648.jpg">
<style>
h1{
    color: white;
}
form{
    width: 700px;
    margin: 0 auto;
}
label{
    display: block;
    margin-bottom: 5px;
}
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
  background-image: url('background.jpg');
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  margin-top: 200px;
  margin-bottom: 100px;
  padding: 16px;
  background-color: rgba(0, 0, 0, 0.3);
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 0.5px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: rgba(0, 0, 0, 0.3);
  text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <?php
    if(isset($_POST["submit"])){
        $email = $_POST["email"];
        $fullName = $_POST["fullname"];
        $password = $_POST["psw"];
        $passwordrepeat = $_POST["psw-repeat"];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $errors = array();
        
        if(empty($fullName) || empty($email) || empty($password) || empty($passwordrepeat)){
            array_push($errors,'All fields are required');
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errors,'Email is not valid');
        }
        if(strlen($password)<8){
            array_push($errors,'Password must be at least 8 character long');
        }
        if($password!==$passwordrepeat){
            array_push($errors,'Password doesn not match');
        }
        require_once "database.php";
        $sql = "SELECT * FROM login_database WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if($rowCount > 0){
          array_push($errors, "Email already exists");
        }
        if(count($errors)>0){
          foreach($errors as $error){
            echo "<div class='alert alert-danger'>$error</div>";
          }
        }else{
          $sql = "INSERT INTO login_database (email, full_name, password) VALUE (?, ?, ?)";
          $stmt = mysqli_stmt_init($conn);
          $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
          if($prepareStmt){
            mysqli_stmt_bind_param($stmt, "sss", $email, $fullName, $passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You are register successfully</div>";
          }else{
            die("Something went wrong");
          }
        }
    }

    ?>
  <form action="register.php" method="post">
    <h1>Register</h1>
    <p style="color: white;">Please fill in this form to create an account.</p>
    <hr>

    <label for="email"><b><p style="color:white">Email</p></b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email" required>

    <label for="fullname"><b><p style="color: white;">Full Name</p></b></label>
    <input type="text" placeholder="Enter Full Name" name="fullname" id="fullname" required>

    <label for="psw"><b><p style="color: white;">Password</p></b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

    <label for="psw-repeat"><b><p style="color:white">Repeat Password</p></b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>

    <hr>
    <p style="color:white">By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

    <button type="submit" name="submit" class="registerbtn">Register</button>
  </form>
  <div class="container signin">
    <p style="color: white;">Already have an account? <a href="#">Sign in</a>.</p>
  </div>
</div>
</body>
</html>