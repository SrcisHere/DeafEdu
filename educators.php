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
   <title>Sp.Educators</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->

<section class="educators">

   <h1 class="heading">Special Educators</h1>

   <form action="search_educator.php" method="post" class="search-educator">
      <input type="text" name="search_educator" maxlength="100" placeholder="search educator..." required>
      <button type="submit" name="search_educator_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container">

      

      <?php
         $select_educators = $conn->prepare("SELECT * FROM `educators`");
         $select_educators->execute();
         if($select_educators->rowCount() > 0){
            while($fetch_educator = $select_educators->fetch(PDO::FETCH_ASSOC)){

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
      ?>
      <div class="box">
         <div class="educator">
            <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_educator['name']; ?></h3>
               <span><?= $fetch_educator['profession']; ?></span>
            </div>
         </div>
         <p>playlists : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents ?></span></p>
         <p>librarys : <span><?= $total_librarys; ?></span></p>
         <p>total pdfs : <span><?= $total_notes ?></span></p>
         <p>total likes : <span><?= $total_likes ?></span></p>
         <p>total comments : <span><?= $total_comments ?></span></p>
         <form action="educator_profile.php" method="post">
            <input type="hidden" name="educator_email" value="<?= $fetch_educator['email']; ?>">
            <input type="submit" value="view profile" name="educator_fetch" class="inline-btn">
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no educators found!</p>';
         }
      ?>

   </div>

</section>

<!-- teachers section ends -->






























<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>