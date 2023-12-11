<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
  $tutor_id = $_COOKIE['tutor_id'];
} else {
  $tutor_id = '';
  header('location:login.php');
}

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents =
  $select_contents->rowCount();
$select_playlists = $conn->prepare("SELECT * FROM
`playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();
$select_likes =
  $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$tutor_id]);
$total_likes = $select_likes->rowCount();
$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id =
?");
$select_comments->execute([$tutor_id]);
$total_comments =
  $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/admin_style.css" />
</head>

<body>
  <?php include '../components/admin_header.php'; ?>

  <section class="dashboard">
    <h1 class="heading">Dashboard</h1>
    <div class="helo">
      <h3>Welcome To Becoming Part Of The Aktual Cendekia Course Mentor</h3>
      <h4>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, ipsam. Perspiciatis hic ullam nisi vitae nemo dolore rerum odio harum consequuntur sunt! Fuga odio optio laborum aut ea cupiditate quos.</h4>
      <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
      <h3><?= $fetch_profile['name']; ?></h3>
      <span><?= $fetch_profile['profession']; ?></span>
    </div>

    <div class="box-container">
      <div class="box">
        <h3><?= $total_contents; ?></h3>
        <p>Total Contents</p>
        <a href="add_content.php" class="btn">Add New Content</a>
      </div>

      <div class="box">
        <h3><?= $total_playlists; ?></h3>
        <p>Total Playlists</p>
        <a href="add_playlist.php" class="btn">Add New Playlist</a>
      </div>

      <div class="box">
        <h3><?= $total_likes; ?></h3>
        <p>Total Likes</p>
        <a href="contents.php" class="btn">View Contents</a>
      </div>

      <div class="box">
        <h3><?= $total_comments; ?></h3>
        <p>Total Comments</p>
        <a href="comments.php" class="btn">View Comments</a>
      </div>

      <div class="box">
        <h3>Quick Select</h3>
        <p>Login or Register</p>
        <div class="flex-btn">
          <a href="login.php" class="option-btn">Login</a>
          <a href="register.php" class="option-btn">Register</a>
        </div>
      </div>
    </div>
  </section>

  <?php include '../components/footer.php'; ?>

  <script src="../js/admin_script.js"></script>
</body>

</html>