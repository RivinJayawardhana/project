<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])) {
   $id = create_unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $c_pass = sha1($_POST['c_pass']);
   
   $select_users = $conn->prepare("SELECT * FROM users WHERE email = ?");
   $select_users->execute([$email]);

   if($select_users->rowCount() > 0) {
      $warning_msg[] = 'Email already taken!';
   } else {
      if($pass != $c_pass) {
         $warning_msg[] = 'Passwords do not match!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO users (id, name, number, email, password) VALUES (?, ?, ?, ?, ?)");
         $insert_user->execute([$id, $name, $number, $email, $c_pass]);

         if($insert_user) {
            header('location:login.php'); // Redirect to login page after registration
            exit;
         } else {
            $error_msg[] = 'Registration failed!';
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
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3>Create an Account!</h3>
      <input type="tel" name="name" required maxlength="50" placeholder="enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="box">
      <p>Already have an account? <a href="login.html">Login Now</a></p>
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>