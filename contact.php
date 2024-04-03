<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="https://api.web3forms.com/submit" method="POST">
         <h3>get in touch</h3>
         <input type="hidden" name="access_key" value="8abdc260-621f-4fdf-a67b-18facc8e9fee">
         <input type="text" placeholder="enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="enter your email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="enter your number" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="send message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

   <div class="box">
         <i class="fas fa-phone"></i>
         <h3>Phone number</h3>
         <a href="tel:+91 8401543154">+91 84015 43154</a>
         <a href="tel:+91 9664636048">+91 96646 36048</a>
         <a href="tel:+91 9825909736">+91 98259 09736</a>
      </div>
      
      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>E-mail address</h3>
         <a href="mailto:shahdimple1976@gmail.com">shahdimple1976@gmail.com</a>
         <a href="mailto:varshasoni3333@gmail.com">varshasoni3333@gmail.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>School Address</h3>
         <a href="https://www.google.com/maps/place/Dhanvantri+School+for+specially+challenged+children,Bhuj/@23.2190136,69.6463045,17z/data=!3m1!4b1!4m6!3m5!1s0x39511e39079b975f:0x9adb7f0a62cf1f00!8m2!3d23.2190087!4d69.6488794!16s%2Fg%2F11c59pj035?entry=ttu">Near Pramukh-Swami Cross Road - Mundra Relocation Site, Bhuj, Gujarat, Bharat(India) - 370001</a>
      </div>


   </div>

</section>

<!-- contact section ends -->











<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>