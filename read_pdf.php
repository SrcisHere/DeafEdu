<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['like_note'])){

   if($user_id != ''){

      $note_id = $_POST['note_id'];
      $note_id = filter_var($note_id, FILTER_SANITIZE_STRING);

      $select_note = $conn->prepare("SELECT * FROM `note` WHERE id = ? LIMIT 1");
      $select_note->execute([$note_id]);
      $fetch_note = $select_note->fetch(PDO::FETCH_ASSOC);

      $educator_id = $fetch_note['educator_id'];

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND note_id = ?");
      $select_likes->execute([$user_id, $note_id]);

      if($select_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND note_id = ?");
         $remove_likes->execute([$user_id, $note_id]);
         $message[] = 'removed from likes!';
      }else{
         $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, educator_id, note_id) VALUES(?,?,?)");
         $insert_likes->execute([$user_id, $educator_id, $note_id]);
         $message[] = 'added to likes!';
      }

   }else{
      $message[] = 'please login first!';
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" note="IE=edge">
   <meta name="viewport" note="width=device-width, initial-scale=1.0">
   <title>Note</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>


<!--  pdf section starts  -->

<section class="read-pdf">

   <?php
      $select_note = $conn->prepare("SELECT * FROM `note` WHERE id = ? AND status = ?");
      $select_note->execute([$get_id, 'active']);
      if($select_note->rowCount() > 0){
         while($fetch_note = $select_note->fetch(PDO::FETCH_ASSOC)){
            $note_id = $fetch_note['id'];

            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE note_id = ?");
            $select_likes->execute([$note_id]);
            $total_likes = $select_likes->rowCount();  

            $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND note_id = ?");
            $verify_likes->execute([$user_id, $note_id]);

            $select_educator = $conn->prepare("SELECT * FROM `educators` WHERE id = ? LIMIT 1");
            $select_educator->execute([$fetch_note['educator_id']]);
            $fetch_educator = $select_educator->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="pdf-details">
      <a href="uploaded_files/<?= $fetch_note['pdf']; ?>" class="pdf" download>
         <i class="inline-btn"><img src="uploaded_files/<?= $fetch_note['thumb']; ?>" alt="">Download Note</i>
      </a>
      <h3 class="title"><?= $fetch_note['title']; ?></h3>
      <div class="info">
         <p><i class="fas fa-calendar"></i><span><?= $fetch_note['date']; ?></span></p>
         <!-- <p><i class="fas fa-heart"></i><span><?= $total_likes; ?> likes</span></p> -->
      </div>
      <div class="educator">
         <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_educator['name']; ?></h3>
            <span><?= $fetch_educator['profession']; ?></span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <input type="hidden" name="note_id" value="<?= $note_id; ?>">
         <a href="library.php?get_id=<?= $fetch_note['library_id']; ?>" class="inline-btn">view library</a>
         
      </form>
      <div class="description"><p><?= $fetch_note['description']; ?></p></div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no pdfs added yet!</p>';
      }
   ?>

</section>

<!--  pdf section ends -->







<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>