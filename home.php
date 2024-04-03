<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

$select_bookmarknote = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmarknote->execute([$user_id]);
$total_bookmarkednote = $select_bookmarknote->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

   <!-- <div id="loader">   
      <h1>Hello,Learners!!</h1>
      <h1>DeafAcademy is for you</h1>
      <h1>"I can do it.."</h1>
   </div>  -->


<section class="quick-select">

   <h1 class="heading">quick options</h1>

   <div class="box-container">
      
      <?php
         if($user_id != ''){
      ?>
      
      <div class="box">
         <h3 class="title">playlists and librarys</h3>
         <p>saved playlists  <span></span></p>
         <a href="bookmark.php" class="inline-btn">view playlists</a>
         <p>saved librarys  <span></span></p>
         <a href="bookmarknote.php" class="inline-btn">view librarys</a>
      </div>

      <div id="slider">
         <figure>
            <img src="images/ifoto-ai_1705858023533.png">
            <img src="images/ifoto-ai_1705857745556.png">
            <img src="images/Illustrations-In-Person.png">
            <img src="images/deaf edu 03-PhotoRoom.png-PhotoRoom.png">
            <img src="images/deaf_05-removebg-preview.png">
         </figure>
      </div>

      <div class="box">
         <h3 class="title">likes and comments</h3>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">view likes</a>
         <p>total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">view comments</a>
      </div>
      <?php
         }else{ 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>
      <?php
      }
      ?>

      

   </div>

</section>


<section class="courses">

   <h1 class="heading">latest courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
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

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">view more</a>
   </div>

</section>


<section class="notecourses">

   <h1 class="heading">latest notes</h1>

   <div class="box-container">

      <?php
         $select_notecourses = $conn->prepare("SELECT * FROM `library` WHERE status = ? ORDER BY date DESC LIMIT 6");
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
         echo '<p class="empty">no notes added yet!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="note_courses.php" class="inline-option-btn">view more</a>
   </div>

</section>



<?php include 'components/footer.php'; ?>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
   function googleTranslateElementInit(){
      new google.translate.TranslateElement({
         pageLanguage:"en"
      },'google_translate_element');
   }
   const languageChanger=() =>{
   var language=document.getElementById("google_translate_element").value;
   // alert(language);
   var selectField=document.querySelector("#google_translate_element select");
   for( var i=0; i<selectField.children.length;i++)
   {
      var option=selectField.children[i];
      if(option.value==language){
         selectField.selectedIndex=i;
         selectField.dispatchEvent(new Event('change'));
         break;
      }
   }
   }
</script>
   
</body>
</html>