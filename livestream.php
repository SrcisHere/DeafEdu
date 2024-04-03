<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>LiveStream</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   
</head>
<body>

<?php include 'components/user_header.php'; ?>
<div class="background">
   <h2>under construction live stream</h2>
</div>
<section class="live-stream">

   <div class="video-container">
      <div class="video">
         <video src="https://www.youtube.com/watch?v=O2dC6XQyeeY" auto controls poster="images/lscover.jpg" id="video"></video>
      </div>
      <h3 class="title">Live Class </h3>
</section>

<!-- <div class="video-details">
      <video src="uploaded_files/<?= $fetch_livestream['stream_url']; ?>" class="video" controls autoplay></video>
      <h3 class="title"><?= $fetch_livestream['title']; ?></h3>
      <div class="info">
         <p><i class="fas fa-calendar"></i><span><?= $fetch_livestream['date']; ?></span></p>
      </div>
      <div class="educator">
         <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_educator['name']; ?></h3>
            <span><?= $fetch_educator['profession']; ?></span>
         </div>
      </div>
      </form>
      <div class="description"><p><?= $fetch_livestream['description']; ?></p></div>
   </div> -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>