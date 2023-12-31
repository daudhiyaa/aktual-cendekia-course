<?php

   include '../components/connect.php';

   if(isset($_COOKIE['tutor_id'])){
      $tutor_id = $_COOKIE['tutor_id'];
   }else{
      $tutor_id = '';
      header('location:login.php');
   }

if(isset($_POST['submit'])){

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
   $select_tutor->execute([$tutor_id]);
   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_tutor['password'];
   $prev_image = $fetch_tutor['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_UNSAFE_RAW);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_UNSAFE_RAW);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_UNSAFE_RAW);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `tutors` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $tutor_id]);
      $message[] = 'USERNAME UPDATED SUCCESFULLY!';
   }

   if(!empty($profession)){
      $update_profession = $conn->prepare("UPDATE `tutors` SET profession = ? WHERE id = ?");
      $update_profession->execute([$profession, $tutor_id]);
      $message[] = 'PROFFESSION UPDATED SUCCESFULLY!';
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `tutors` WHERE id = ? AND email = ?");
      $select_email->execute([$tutor_id, $email]);
      if($select_email->rowCount() > 0){
         $message[] = 'EMAIL ALRADY TAKEN!';
      }else{
         $update_email = $conn->prepare("UPDATE `tutors` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $tutor_id]);
         $message[] = 'EMAIL UPDATE SUCCESFULLY!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_UNSAFE_RAW);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'IMAGE SIZE TOO LARGE!';
      }else{
         $update_image = $conn->prepare("UPDATE `tutors` SET `image` = ? WHERE id = ?");
         $update_image->execute([$rename, $tutor_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image != '' AND $prev_image != $rename){
            unlink('../uploaded_files/'.$prev_image);
         }
         $message[] = 'IMAGE UPDATED SUCCESFULLY!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_UNSAFE_RAW);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_UNSAFE_RAW);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_UNSAFE_RAW);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'OLD PASSWORD NOT MATCHED!';
      }elseif($new_pass != $cpass){
         $message[] = 'CONFIRM PASSWORD NOT MATCHED!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $tutor_id]);
            $message[] = 'PASSWORD UPDATED SUCCESFULLY!';
         }else{
            $message[] = 'PLEASE ENTER A NEW PASSWORD!';
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
   <title>UPDATE PROFILE</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- register section starts  -->

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>UPDATE PROFILE</h3>
      <div class="flex">
         <div class="col">
            <p>YOUR NAME</p>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50"  class="box">
            <p>YOUR PROFESSION</p>
            <select name="profession" class="box">
               <option value="" selected><?= $fetch_profile['profession']; ?></option>
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
            <p>YOUR EMAIL</p>
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="100"  class="box">
         </div>
         <div class="col">
            <p>OLD PASSWORD :</p>
            <input type="password" name="old_pass" placeholder="ENTER YOUR OLD PASSWORD" maxlength="100"  class="box">
            <p>NEW PASSWORD :</p>
            <input type="password" name="new_pass" placeholder="ENTER YOUR NEW PASSWORD" maxlength="100"  class="box">
            <p>CONFIRM PASSWORD :</p>
            <input type="password" name="cpass" placeholder="CONFIRM YOUR NEW PASSWORD" maxlength="100"  class="box">
         </div>
      </div>
      <p>UPDATE PIC :</p>
      <input type="file" name="image" accept="image/*"  class="box">
      <input type="submit" name="submit" value="UPDATE NOW" class="btn">
   </form>

</section>

<!-- registe section ends -->
<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
   
</body>
</html>