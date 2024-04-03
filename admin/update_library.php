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
   header('location:library.php');
}

if(isset($_POST['submit'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);

   $update_library = $conn->prepare("UPDATE `library` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_library->execute([$title, $description, $status, $get_id]);

   $old_image = $_POST['old_image'];
   $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `library` SET thumb = ? WHERE id = ?");
         $update_image->execute([$rename, $get_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($old_image != '' AND $old_image != $rename){
            unlink('../uploaded_files/'.$old_image);
         }
      }
   } 

   $message[] = 'library updated!';  

}

if(isset($_POST['delete'])){
   $delete_id = $_POST['library_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $delete_library_thumb = $conn->prepare("SELECT * FROM `library` WHERE id = ? LIMIT 1");
   $delete_library_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_library_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE library_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_library = $conn->prepare("DELETE FROM `library` WHERE id = ?");
   $delete_library->execute([$delete_id]);
   header('location:librarys.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update library</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">update library</h1>

   <?php
         $select_library = $conn->prepare("SELECT * FROM `library` WHERE id = ?");
         $select_library->execute([$get_id]);
         if($select_library->rowCount() > 0){
         while($fetch_library = $select_library->fetch(PDO::FETCH_ASSOC)){
            $library_id = $fetch_library['id'];
            $count_pdfs = $conn->prepare("SELECT * FROM `note` WHERE library_id = ?");
            $count_pdfs->execute([$library_id]);
            $total_pdfs = $count_pdfs->rowCount();
      ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_library['thumb']; ?>">
      <p>library status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_library['status']; ?>" selected><?= $fetch_library['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>library title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter library title" value="<?= $fetch_library['title']; ?>" class="box">
      <p>library description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fetch_library['description']; ?></textarea>
      <p>library thumbnail <span>*</span></p>
      <div class="thumb">
         <span><?= $total_pdfs; ?></span>
         <img src="../uploaded_files/<?= $fetch_library['thumb']; ?>" alt="">
      </div>
      <input type="file" name="image" accept="image/*" class="box">
      <input type="submit" value="update library" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this library?');" name="delete">
         <a href="view_library.php?get_id=<?= $library_id; ?>" class="option-btn">view library</a>
      </div>
   </form>
   <?php
      } 
   }else{
      echo '<p class="empty">no library added yet!</p>';
   }
   ?>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>