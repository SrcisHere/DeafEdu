<?php

   include 'connect.php';

   setcookie('educator_id', '', time() - 1, '/');

   header('location:../admin/login.php');

?>