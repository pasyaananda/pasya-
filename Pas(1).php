<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pas.css">
    <title>Uap</title>
</head>
<body>
    <div class="signup-box">
        <h1>Sign up</h1>
        <form action="signup.php" method="post">
            <label>Username</label>
            <input type="text" placeholder="Masukan Username" name="username" required>
            <label>Password</label>
            <input type="password" placeholder="Masukan Password" name="password" required>
            <label>Your Email address</label>
            <input type="text" placeholder="Masukan Email" name="user_email" required>
            <input type="submit" name="submit" value="Sign Up">
</form>
    </div>
    </div>


<?php
    include "koneksi.php";

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_email = $_POST['user_email'];

        $result = mysqli_query($koneksi, "INSERT INTO user_data(username,password,user_email) 
        VALUES('$username','$password','$user_email')");
         if ($result) {
            header("Location: index(1).php"); // Redirect to login.php
            exit(); // Stop further execution of the script
        } else {
            echo "Error: " . mysqli_error($koneksi);
          }
        }


?>
</body>
</html>