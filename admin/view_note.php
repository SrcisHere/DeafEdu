<?php

include '../components/connect.php';

if(isset($_COOKIE['educator_id'])){
   $educator_id = $_COOKIE['educator_id'];
}else{
   $educator_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:notes.php');
}

if(isset($_POST['delete_pdf'])){

   $delete_id = $_POST['pdf_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $delete_pdf_thumb = $conn->prepare("SELECT thumb FROM `note` WHERE id = ? LIMIT 1");
   $delete_pdf_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_pdf_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);

   $delete_pdf = $conn->prepare("SELECT pdf FROM `note` WHERE id = ? LIMIT 1");
   $delete_pdf->execute([$delete_id]);
   $fetch_pdf = $delete_pdf->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_pdf['pdf']);

   $delete_note = $conn->prepare("DELETE FROM `note` WHERE id = ?");
   $delete_note->execute([$delete_id]);
   header('location:notes.php');
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>view note</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="view-note">

   <?php
       $select_note = $conn->prepare("SELECT * FROM `note` WHERE id = ? AND educator_id = ?");
       $select_note->execute([$get_id, $educator_id]);
      if($select_note->rowCount() > 0){
      while($fetch_note = $select_note->fetch(PDO::FETCH_ASSOC)){
            $pdf_id = $fetch_note['id'];

      //       $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE educator_id = ? AND note_id = ?");
      //       $count_likes->execute([$educator_id, $pdf_id]);
      //       $total_likes = $count_likes->rowCount();

      //       $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE educator_id = ? AND note_id = ?");
      //       $count_comments->execute([$educator_id, $pdf_id]);
      //       $total_comments = $count_comments->rowCount();
   ?>
   <div class="container">
      <a href="../uploaded_files/<?= $fetch_note['pdf']; ?>" class="pdf" download>
         <i class="inline-btn"><img src="../uploaded_files/<?= $fetch_note['thumb']; ?>" alt="">Download Note</i>
      </a>
      <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_note['date']; ?></span></div>
      <h3 class="title"><?= $fetch_note['title']; ?></h3>
      <div class="description"><?= $fetch_note['description']; ?></div>
      <form action="" method="post">
         <div class="flex-btn">
            <input type="hidden" name="pdf_id" value="<?= $pdf_id; ?>">
            <a href="update_note.php?get_id=<?= $pdf_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this pdf?');" name="delete_pdf">
         </div>
      </form>
   </div>
   <?php 
    }
   }else{
      echo '<p class="empty">no notes added yet! <a href="add_note.php" class="btn" style="margin-top: 1.5rem;">add pdfs</a></p>';
   }
      
   ?>

</section>







<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>