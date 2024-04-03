<?php

include '../components/connect.php';

if(isset($_COOKIE['educator_id'])){
   $educator_id = $_COOKIE['educator_id'];
}else{
   $educator_id = '';
   header('location:login.php');
}

if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND educator_id = ? LIMIT 1");
   $verify_playlist->execute([$delete_id, $educator_id]);

   if($verify_playlist->rowCount() > 0){

   

   $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_playlist_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
   $delete_playlist->execute([$delete_id]);
   $message[] = 'playlist deleted!';
   }else{
      $message[] = 'playlist already deleted!';
   }
}
if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);
   if($verify_video->rowCount() > 0){
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_video['video']);
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);
      $message[] = 'video deleted!';
   }else{
      $message[] = 'video already deleted!';
   }

}
if(isset($_POST['delete_library'])){
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
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">

   <h1 class="heading">playlists</h1>

   <div class="box-container">
   
      <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search'];
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search}%' AND educator_id = ? ORDER BY date DESC");
         $select_playlist->execute([$educator_id]);
         if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete_playlist" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">view playlist</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">no playlists found!</p>';
      }}else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>

   </div>

</section>
<section class="contents">

   <h1 class="heading">contents</h1>

   <div class="box-container">

   <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
      $search = $_POST['search'];
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE title LIKE '%{$search}%' AND educator_id = ? ORDER BY date DESC");
      $select_videos->execute([$educator_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">view content</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no contents founds!</p>';
      }
   }else{
      echo '<p class="empty">please search something!</p>';
   }
   ?>

   </div>

</section>
<section class="librarys">

   <h1 class="heading">librarys</h1>

   <div class="box-container">
   
      <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search'];
         $select_library = $conn->prepare("SELECT * FROM `library` WHERE title LIKE '%{$search}%' AND educator_id = ? ORDER BY date DESC");
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
            <input type="submit" value="delete_library" class="delete-btn" onclick="return confirm('delete this library?');" name="delete">
         </form>
         <a href="view_library.php?get_id=<?= $library_id; ?>" class="btn">view library</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">no librarys found!</p>';
      }}else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>

   </div>

</section>
<section class="notes">

   <h1 class="heading">notes</h1>

   <div class="box-container">

   <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
      $search = $_POST['search'];
      $select_pdfs = $conn->prepare("SELECT * FROM `note` WHERE title LIKE '%{$search}%' AND educator_id = ? ORDER BY date DESC");
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
         echo '<p class="empty">no notes founds!</p>';
      }
   }else{
      echo '<p class="empty">please search something!</p>';
   }
   ?>

   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>