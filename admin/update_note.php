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
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $pdf_id = $_POST['pdf_id'];
   $pdf_id = filter_var($pdf_id, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $library = $_POST['library'];
   $library = filter_var($library, FILTER_SANITIZE_STRING);

   $update_note = $conn->prepare("UPDATE `note` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_note->execute([$title, $description, $status, $pdf_id]);

   if(!empty($library)){
      $update_library = $conn->prepare("UPDATE `note` SET library_id = ? WHERE id = ?");
      $update_library->execute([$library, $pdf_id]);
   }

   $old_thumb = $_POST['old_thumb'];
   $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_thumb = $conn->prepare("UPDATE `note` SET thumb = ? WHERE id = ?");
         $update_thumb->execute([$rename_thumb, $pdf_id]);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         if($old_thumb != '' AND $old_thumb != $rename_thumb){
            unlink('../uploaded_files/'.$old_thumb);
         }
      }
   }

   $old_pdf = $_POST['old_pdf'];
   $old_pdf = filter_var($old_pdf, FILTER_SANITIZE_STRING);
   $pdf = $_FILES['pdf']['name'];
   $pdf = filter_var($pdf, FILTER_SANITIZE_STRING);
   $pdf_ext = pathinfo($pdf, PATHINFO_EXTENSION);
   $rename_pdf = unique_id().'.'.$pdf_ext;
   $pdf_tmp_name = $_FILES['pdf']['tmp_name'];
   $pdf_folder = '../uploaded_files/'.$rename_pdf;

   if(!empty($pdf)){
      $update_pdf = $conn->prepare("UPDATE `note` SET pdf = ? WHERE id = ?");
      $update_pdf->execute([$rename_pdf, $pdf_id]);
      move_uploaded_file($pdf_tmp_name, $pdf_folder);
      if($old_pdf != '' AND $old_pdf != $rename_pdf){
         unlink('../uploaded_files/'.$old_pdf);
      }
   }

   $message[] = 'note updated!';

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

   $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE note_id = ?");
   $delete_likes->execute([$delete_id]);
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE note_id = ?");
   $delete_comments->execute([$delete_id]);

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
   <title>Update pdf</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="pdf-form">

   <h1 class="heading">update note</h1>

   <?php
      $select_pdfs = $conn->prepare("SELECT * FROM `note` WHERE id = ? AND educator_id = ?");
      $select_pdfs->execute([$get_id, $educator_id]);
      if($select_pdfs->rowCount() > 0){
         while($fecth_pdfs = $select_pdfs->fetch(PDO::FETCH_ASSOC)){ 
            $pdf_id = $fecth_pdfs['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pdf_id" value="<?= $fecth_pdfs['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fecth_pdfs['thumb']; ?>">
      <input type="hidden" name="old_pdf" value="<?= $fecth_pdfs['pdf']; ?>">
      <p>update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_pdfs['status']; ?>" selected><?= $fecth_pdfs['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter pdf title" class="box" value="<?= $fecth_pdfs['title']; ?>">
      <p>update description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fecth_pdfs['description']; ?></textarea>
      <p>update library</p>
      <select name="library" class="box">
         <option value="<?= $fecth_pdfs['library_id']; ?>" selected>--select library</option>
         <?php
         $select_librarys = $conn->prepare("SELECT * FROM `library` WHERE educator_id = ?");
         $select_librarys->execute([$educator_id]);
         if($select_librarys->rowCount() > 0){
            while($fetch_library = $select_librarys->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_library['id']; ?>"><?= $fetch_library['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>no library created yet!</option>';
         }
         ?>
      </select>
      <img src="../uploaded_files/<?= $fecth_pdfs['thumb']; ?>" alt="">
      <p>update thumbnail</p>
      <input type="file" name="thumb" accept="image/*" class="box">
      <pdf src="../uploaded_files/<?= $fecth_pdfs['pdf']; ?>" controls></pdf>
      <p>update pdf</p>
      <input type="file" name="pdf" accept="pdf/*" class="box">
      <input type="submit" value="update note" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_note.php?get_id=<?= $pdf_id; ?>" class="option-btn">view note</a>
         <input type="submit" value="delete note" name="delete_pdf" class="delete-btn">
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">pdf not found! <a href="add_note.php" class="btn" style="margin-top: 1.5rem;">add pdfs</a></p>';
      }
   ?>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>