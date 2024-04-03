<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Panel</title>
    <link rel="stylesheet" href="keyboard/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 40;
            padding: 20px;
        }
        body h2{
            font-size: 70px;
            color:#8e44ad;
        }
        
        #messageList {
            list-style-type: none;
            padding: 0;
            font-size: 40px;
        }
        .reset{
            width: 20%;
            height: 30%;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<h2>Note</h2>

<div id="chatMessages"></div>
<ul id="messageList"></ul>
<button class="reset" onclick="resetMessages()">Delete Note</button>
<script src="client.js"></script>

</body>
</html>
