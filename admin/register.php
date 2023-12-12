<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_UNSAFE_RAW);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_UNSAFE_RAW);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_UNSAFE_RAW);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_UNSAFE_RAW);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_UNSAFE_RAW);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_UNSAFE_RAW);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'EMAIL ALREADY TAKEN!';
   }else{
      if($pass != $cpass){
         $message[] = 'CONFIRM PASSWORD NOT MATCHED!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'NEW TUTOR REGISTERED! PLEASE LOGIN NOW';
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
   <title>REGISTER</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- register section starts  -->

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>REGISTER NEW</h3>
      <div class="flex">
         <div class="col">
            <p>YOUR NAME <span>*</span></p>
            <input type="text" name="name" placeholder="ENTER YOUR NAME" maxlength="100" required class="box">
            <p>YOUR PROFESSION<span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- SELECT YOUR PROFESSION</option>
               <option value="developer">Developer</option>
               <option value="desginer">Desginer</option>
               <option value="musician">Musician</option>
               <option value="biologist">Biologist</option>
               <option value="teacher">Teacher</option>
               <option value="engineer">Engineer</option>
               <option value="lawyer">Lawyer</option>
               <option value="accountant">Accountant</option>
               <option value="doctor">Doctor</option>
               <option value="journalist">Journalist</option>
               <option value="photographer">Photographer</option>
            </select>
            <p>YOUR EMAIL<span>*</span></p>
            <input type="email" name="email" placeholder="ENTER YOUR EMAIL" maxlength="100" required class="box">
         </div>
         <div class="col">
            <p>YOUR PASSWORD<span>*</span></p>
            <input type="password" name="pass" placeholder="ENTER YOUR PASSWORD" maxlength="100" required class="box">
            <p>CONFIRM PASSWORD<span>*</span></p>
            <input type="password" name="cpass" placeholder="CONFIRM YOUR PASSWORD" maxlength="100" required class="box">
            <p>SELECT PIC <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link"> ALREADY HAVE AN ACCOUNT?<a href="login.php"> LOGIN </a></p>
      <input type="submit" name="submit" value="REGISTER" class="btn">
   </form>

</section>

<!-- registe section ends -->












<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>