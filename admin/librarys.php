<?php

include '../components/connect.php';

if(isset($_COOKIE['educator_id'])){
   $educator_id = $_COOKIE['educator_id'];
}else{
   $educator_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){
   $delete_id = $_POST['library_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_library = $conn->prepare("SELECT * FROM `library` WHERE id = ? AND educator_id = ? LIMIT 1");
   $verify_library->execute([$delete_id, $educator_id]);

   if($verify_library->rowCount() > 0){

   

   $delete_library_thumb = $conn->prepare("SELECT * FROM `library` WHERE id = ? LIMIT 1");
   $delete_library_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_library_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE library_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_library = $conn->prepare("DELETE FROM `library` WHERE id = ?");
   $delete_library->execute([$delete_id]);
   $message[] = 'library deleted!';
   }else{
      $message[] = 'library already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>librarys</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="librarys">

   <h1 class="heading">added librarys</h1>

   <div class="box-container">
   
      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">create new library</h3>
         <a href="add_library.php" class="btn">add library</a>
      </div>

      <?php
         $select_library = $conn->prepare("SELECT * FROM `library` WHERE educator_id = ? ORDER BY date DESC");
         $select_library->execute([$educator_id]);
         if($select_library->rowCount() > 0){
         while($fetch_library = $select_library->fetch(PDO::FETCH_ASSOC)){
            $library_id = $fetch_library['id'];
            $count_pdfs = $conn->prepare("SELECT * FROM `note` WHERE library_id = ?");
            $count_pdfs->execute([$library_id]);
            $total_pdfs = $count_pdfs->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_library['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_library['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_library['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_library['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_pdfs; ?></span>
            <img src="../uploaded_files/<?= $fetch_library['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_library['title']; ?></h3>
         <p class="description"><?= $fetch_library['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="library_id" value="<?= $library_id; ?>">
            <a href="update_library.php?get_id=<?= $library_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this library?');" name="delete">
         </form>
         <a href="view_library.php?get_id=<?= $library_id; ?>" class="btn">view library</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">no library added yet!</p>';
      }
      ?>

   </div>

</section>







<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.librarys .box-container .box .description').forEach(note => {
      if(note.innerHTML.length > 100) note.innerHTML = note.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>