<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin</a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
      <div class="language-selector">
         <select id="google_translate_element" name="languages" onchange="languageChanger()">
            <option selected disabled>Choose Language</option>
            <option value="en">English</option>
           <option value="hi">Hindi</option>
           <option value="gu">Gujarati</option>
         </select>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `educators` WHERE id = ?");
            $select_profile->execute([$educator_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span><?= $fetch_profile['profession']; ?></span>
         <a href="profile.php" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `educators` WHERE id = ?");
            $select_profile->execute([$educator_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span><?= $fetch_profile['profession']; ?></span>
         <a href="profile.php" class="btn">view profile</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="dashboard.php"><i style="font-size:24px" class="fas fa-home"></i><span>Home</span></a>
      <a href="playlists.php"><i style="font-size:24px" class="fa-solid fa-bars-staggered"></i><span>Playlists</span></a>
      <a href="contents.php"><i style="font-size:24px" class="fas fa-sign-language"></i><span>Contents</span></a>
      <a href="librarys.php"><i style="font-size:24px" class="fas fa-book"></i><span>Library</span></a>
      <a href="notes.php"><i style="font-size:24px"class="fa">&#xf1c1;</i><span>Notes</span></a>
      <a href="adminlivestream.php"><i style="font-size:24px" class="fa fa-play"></i><span>Live Stream</span></a>
      <a href="comments.php"><i style="font-size:24px" class="fas fa-comment"></i><span>Comments</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i style="font-size:24px" class="fas fa-right-from-bracket"></i><span>Logout</span></a>
   </nav>

</div>

<!-- side bar section ends -->