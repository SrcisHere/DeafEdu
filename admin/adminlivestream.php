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

   $add_livestream = $conn->prepare("INSERT INTO `livestream`(id, educator_id, stream_url, title, description, status) VALUES(?,?,?,?,?,?)");
   $add_livestream->execute([$id, $educator_id, $stream_url, $title, $description, $status]);
   $message[] = 'new live stream added!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add live stream</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
<div class="background">
   <h2>under construction live stream</h2>
</div>
<section class="video-form">

   <h1 class="heading">Live Stream</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>video status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>Stream URL<span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter stream url" class="box">
      <p>video title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box">
      <p>video description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      </select>
      <input type="submit" value="submit" name="submit" class="btn">
   </form>
    
      </form>
</section>





<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>