<?php

include '../components/connect.php';

if(isset($_COOKIE['educator_id'])){
   $educator_id = $_COOKIE['educator_id'];
}else{
   $educator_id = '';
   header('location:login.php');
}

if(isset($_POST['delete_pdf'])){
   $delete_id = $_POST['pdf_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_pdf = $conn->prepare("SELECT * FROM `note` WHERE id = ? LIMIT 1");
   $verify_pdf->execute([$delete_id]);
   if($verify_pdf->rowCount() > 0){
      $delete_pdf_thumb = $conn->prepare("SELECT * FROM `note` WHERE id = ? LIMIT 1");
      $delete_pdf_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_pdf_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_pdf = $conn->prepare("SELECT * FROM `note` WHERE id = ? LIMIT 1");
      $delete_pdf->execute([$delete_id]);
      $fetch_pdf = $delete_pdf->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_pdf['pdf']);
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE note_id = ?");
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE note_id = ?");
      $delete_comments->execute([$delete_id]);
      $delete_note = $conn->prepare("DELETE FROM `note` WHERE id = ?");
      $delete_note->execute([$delete_id]);
      $message[] = 'pdf deleted!';
   }else{
      $message[] = 'pdf already deleted!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Notes</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="notes">

   <h1 class="heading">your notes</h1>

   <div class="box-container">

      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">create new note</h3>
         <a href="add_note.php" class="btn">add note</a>
      </div>

      <?php
         $select_pdfs = $conn->prepare("SELECT * FROM `note` WHERE educator_id = ? ORDER BY date DESC");
         $select_pdfs->execute([$educator_id]);
         if($select_pdfs->rowCount() > 0){
            while($fecth_pdfs = $select_pdfs->fetch(PDO::FETCH_ASSOC)){ 
               $pdf_id = $fecth_pdfs['id'];
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_pdfs['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_pdfs['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_pdfs['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_pdfs['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_pdfs['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_pdfs['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="pdf_id" value="<?= $pdf_id; ?>">
            <a href="update_note.php?get_id=<?= $pdf_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this pdf?');" name="delete_pdf">
         </form>
         <a href="view_note.php?get_id=<?= $pdf_id; ?>" class="btn">view note</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no notes added yet!</p>';
         }
      ?>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>