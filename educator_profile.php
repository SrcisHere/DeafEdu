<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['educator_fetch'])){

   $educator_email = $_POST['educator_email'];
   $educator_email = filter_var($educator_email, FILTER_SANITIZE_STRING);
   $select_educator = $conn->prepare('SELECT * FROM `educators` WHERE email = ?');
   $select_educator->execute([$educator_email]);

   $fetch_educator = $select_educator->fetch(PDO::FETCH_ASSOC);
   $educator_id = $fetch_educator['id'];

   $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE educator_id = ?");
   $count_playlists->execute([$educator_id]);
   $total_playlists = $count_playlists->rowCount();

   $count_contents = $conn->prepare("SELECT * FROM `content` WHERE educator_id = ?");
   $count_contents->execute([$educator_id]);
   $total_contents = $count_contents->rowCount();

   $count_librarys = $conn->prepare("SELECT * FROM `library` WHERE educator_id = ?");
   $count_librarys->execute([$educator_id]);
   $total_librarys = $count_librarys->rowCount();

   $count_notes = $conn->prepare("SELECT * FROM `note` WHERE educator_id = ?");
   $count_notes->execute([$educator_id]);
   $total_notes = $count_notes->rowCount();

   $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE educator_id = ?");
   $count_likes->execute([$educator_id]);
   $total_likes = $count_likes->rowCount();

   $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE educator_id = ?");
   $count_comments->execute([$educator_id]);
   $total_comments = $count_comments->rowCount();

}else{
   header('location:teachers.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Educator's profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers profile section starts  -->

<section class="educator-profile">

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="educator">
         <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
         <h3><?= $fetch_educator['name']; ?></h3>
         <span><?= $fetch_educator['profession']; ?></span>
      </div>
      <div class="flex">
         <p>total playlists : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents; ?></span></p>
         <p>total librarys : <span><?= $total_librarys; ?></span></p>
         <p>total pdfs : <span><?= $total_notes; ?></span></p>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <p>total comments : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="courses">

   <h1 class="heading">latest courese</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE educator_id = ? AND status = ?");
         $select_courses->execute([$educator_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_educator = $conn->prepare("SELECT * FROM `educators` WHERE id = ?");
               $select_educator->execute([$fetch_course['educator_id']]);
               $fetch_educator = $select_educator->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="educator">
            <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_educator['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->


<section class="notecourses">

   <h1 class="heading">latest notee</h1>

   <div class="box-container">

      <?php
         $select_notecourses = $conn->prepare("SELECT * FROM `library` WHERE educator_id = ? AND status = ?");
         $select_notecourses->execute([$educator_id, 'active']);
         if($select_notecourses->rowCount() > 0){
            while($fetch_notecourse = $select_notecourses->fetch(PDO::FETCH_ASSOC)){
               $notecourse_id = $fetch_notecourse['id'];

               $select_educator = $conn->prepare("SELECT * FROM `educators` WHERE id = ?");
               $select_educator->execute([$fetch_notecourse['educator_id']]);
               $fetch_educator = $select_educator->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="educator">
            <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_educator['name']; ?></h3>
               <span><?= $fetch_notecourse['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_notecourse['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_notecourse['title']; ?></h3>
         <a href="library.php?get_id=<?= $notecourse_id; ?>" class="inline-btn">view library</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no notes added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->






<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>