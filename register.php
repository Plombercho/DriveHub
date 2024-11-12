<?php

include 'dbConnection.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   // $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); //hashed with md5 (lower security)
   $pass = $_POST['password'];
   $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
   // $cpass = mysqli_real_escape_string($conn, md5($_POST['password'])); //hashed with md5 (lower security)
   $cpass = $_POST['cpassword'];
   $cpass_hash = password_hash($cpass, PASSWORD_DEFAULT);

   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass_hash'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'Потребителят вече съществува!'; 
   }else{
      if($pass != $cpass){
         $message[] = 'Паролата не съвпата с паролата за потвърждение!';
      }else{
         $insert = mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$pass_hash')") or die('query failed');

         if($insert){
            $message[] = 'Успешно регистриране!';
            header('location:login.php');
         }else{
            $message[] = 'Грешка при регистрирането!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   <link rel="stylesheet" href="css/loginStyle.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Регистрирай се</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Въведи име" class="box" required>
      <input type="email" name="email" placeholder="Въведи имейл" class="box" required>
      <input type="password" name="password" placeholder="Въведи парола" class="box" required>
      <input type="password" name="cpassword" placeholder="Потвърди паролата" class="box" required>
      <input type="submit" name="submit" value="Регистрирай се" class="btn">
      <p>Имате съществуващ акаунт? <a href="login.php">Влезте сега</a></p>
   </form>

</div>

</body>
</html>