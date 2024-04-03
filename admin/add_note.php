<?php

include '../components/connect.php';

if(isset($_COOKIE['educator_id'])){
   $educator_id = $_COOKIE['educator_id'];
}else{
   $educator_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $library = $_POST['library'];
   $library = filter_var($library, FILTER_SANITIZE_STRING);

   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   $pdf = $_FILES['pdf']['name'];
   $pdf = filter_var($pdf, FILTER_SANITIZE_STRING);
   $pdf_ext = pathinfo($pdf, PATHINFO_EXTENSION);
   $rename_pdf = unique_id().'.'.$pdf_ext;
   $pdf_tmp_name = $_FILES['pdf']['tmp_name'];
   $pdf_folder = '../uploaded_files/'.$rename_pdf;

   if($thumb_size > 2000000){
      $message[] = 'image size is too large!';
   }else{
      $add_library = $conn->prepare("INSERT INTO `note`(id, educator_id, library_id, title, description, pdf, thumb, status) VALUES(?,?,?,?,?,?,?,?)");
      $add_library->execute([$id, $educator_id, $library, $title, $description, $rename_pdf, $rename_thumb, $status]);
      move_uploaded_file($thumb_tmp_name, $thumb_folder);
      move_uploaded_file($pdf_tmp_name, $pdf_folder);
      $message[] = 'new pdf uploaded!';
   }

   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add note</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="pdf-form">

   <h1 class="heading">upload note</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>pdf status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>pdf title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter pdf title" class="box">
      <p>pdf description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>pdf library <span>*</span></p>
      <select name="library" class="box" required>
         <option value="" disabled selected>--select library</option>
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
      <p>select thumbnail <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>select pdf <span>*</span></p>
      <input type="file" name="pdf" accept="pdf/*" required class="box">
      <input type="submit" value="upload pdf" name="submit" class="btn">
   </form>

</section>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>