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
   <title>notecourses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- notecourses section starts  -->

<section class="notecourses">

   <h1 class="heading">all notes</h1>

   <div class="box-container">

      <?php
         $select_notecourses = $conn->prepare("SELECT * FROM `library` WHERE status = ? ORDER BY date DESC");
         $select_notecourses->execute(['active']);
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
         echo '<p class="empty">no notecourses added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- notecourses section ends -->




<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>