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

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND library_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmarknote = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND library_id = ?");
         $remove_bookmarknote->execute([$user_id, $list_id]);
         $message[] = 'library removed!';
      }else{
         $insert_bookmarknote = $conn->prepare("INSERT INTO `bookmark`(user_id, library_id) VALUES(?,?)");
         $insert_bookmarknote->execute([$user_id, $list_id]);
         $message[] = 'library saved!';
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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>library</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- library section starts  -->

<section class="library">

   <h1 class="heading">library details</h1>

   <div class="row">

      <?php
         $select_library = $conn->prepare("SELECT * FROM `library` WHERE id = ? and status = ? LIMIT 1");
         $select_library->execute([$get_id, 'active']);
         if($select_library->rowCount() > 0){
            $fetch_library = $select_library->fetch(PDO::FETCH_ASSOC);

            $library_id = $fetch_library['id'];

            $count_pdfs = $conn->prepare("SELECT * FROM `note` WHERE library_id = ?");
            $count_pdfs->execute([$library_id]);
            $total_pdfs = $count_pdfs->rowCount();

            $select_educator = $conn->prepare("SELECT * FROM `educators` WHERE id = ? LIMIT 1");
            $select_educator->execute([$fetch_library['educator_id']]);
            $fetch_educator = $select_educator->fetch(PDO::FETCH_ASSOC);

            $select_bookmarknote = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND library_id = ?");
            $select_bookmarknote->execute([$user_id, $library_id]);

      ?>

      <div class="col">
         <form action="" method="post" class="save-list">
            <input type="hidden" name="list_id" value="<?= $library_id; ?>">
            <?php
               if($select_bookmarknote->rowCount() > 0){
            ?>
            <button type="submit" name="save_list"><i class="fas fa-bookmark"></i><span>saved</span></button>
            <?php
               }else{
            ?>
               <button type="submit" name="save_list"><i class="far fa-bookmark"></i><span>save library</span></button>
            <?php
               }
            ?>
         </form>
         <div class="thumb">
            <span><?= $total_pdfs; ?>pdfs</span>
            <img src="uploaded_files/<?= $fetch_library['thumb']; ?>" alt="">
         </div>
      </div>

      <div class="col">
         <div class="educator">
            <img src="uploaded_files/<?= $fetch_educator['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_educator['name']; ?></h3>
               <span><?= $fetch_educator['profession']; ?></span>
            </div>
         </div>
         <div class="details">
            <h3><?= $fetch_library['title']; ?></h3>
            <p><?= $fetch_library['description']; ?></p>
            <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_library['date']; ?></span></div>
         </div>
      </div>

      <?php
         }else{
            echo '<p class="empty">this library was not found!</p>';
         }  
      ?>

   </div>

</section>

<!-- library section ends -->

<!-- pdfs container section starts  -->

<section class="pdfs-container">

   <h1 class="heading">library PDFS</h1>

   <div class="box-container">

      <?php
         $select_note = $conn->prepare("SELECT * FROM `note` WHERE library_id = ? AND status = ? ORDER BY date DESC");
         $select_note->execute([$get_id, 'active']);
         if($select_note->rowCount() > 0){
            while($fetch_note = $select_note->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <a href="read_pdf.php?get_id=<?= $fetch_note['id']; ?>" class="box">
         <i class="fas fa-book-reader"></i>
         <img src="uploaded_files/<?= $fetch_note['thumb']; ?>" alt="">
         <h3><?= $fetch_note['title']; ?></h3>
      </a>
      <?php
            }
         }else{
            echo '<p class="empty">no pdfs added yet!</p>';
         }
      ?>

   </div>

</section>

<!-- pdfs container section ends -->











<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>