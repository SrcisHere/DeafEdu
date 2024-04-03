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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="keyboard/style.css">
    <script src="keyboard/app.js" defer></script>
    <script src="keyboard/script.js"></script>
    <script src="admin.js"></script>
    <title>Virtual Keyboard</title>
</head>
<body>
    
    <div class="container">
        <div class="textarea">
            <textarea id="gujaratiInput" placeholder="ગુજરાતી માં લખો....."></textarea>
        </div>
        <div class="keyboard">
            <div class="row">
                <button class="btn">ક</button>
                <button class="btn">ખ</button>
                <button class="btn">ગ</button>
                <button class="btn">ઘ</button>
                <button class="btn">ચ</button>
                <button class="btn">છ</button>
                <button class="btn">જ</button>
                <button class="btn">ઝ</button>
                <button class="btn">ઞ</button>
		        <button class="delete">Delete</button>
            </div>
            <div class="row">
                <button class="btn">ટ</button>
                <button class="btn">ઠ</button>
                <button class="btn">ડ</button>
                <button class="btn">ઢ</button>
                <button class="btn">ણ</button>
                <button class="btn">ત</button>
                <button class="btn">થ</button>
                <button class="btn">દ</button>
                <button class="btn">ધ</button>
                <button class="btn">ન</button>                
            </div>
            <div class="row">
                <button class="btn">પ</button>
                <button class="btn">ફ</button>
                <button class="btn">બ</button>
                <button class="btn">ભ</button>
                <button class="btn">મ</button>                
                <button class="btn">ય</button>
                <button class="btn">ર</button>
                <button class="btn">લ</button>
                <button class="btn">વ</button>
                <button class="btn">શ</button>                
            </div>
            <div class="row">
                <button class="btn">ષ</button>
                <button class="btn">સ</button>
                <button class="btn">હ</button>
                <button class="btn">ળ</button>
                <button class="btn">ક્ષ</button>
		        <button class="btn">જ્ઞ</button>
                <button class="btn">અ</button>
                <button class="btn">આ</button>
                <button class="btn">ઇ</button>
                <button class="btn">ઈ</button>
            </div>
            <div class="row">
                <button class="btn">ઉ</button>
		        <button class="btn">ઊ</button>
                <button class="btn">એ</button>
                <button class="btn">ઐ</button>
                <button class="btn">ઓ</button>
		        <button class="btn">ઔ</button>
                <button class="btn">અં</button>
                <button class="btn">ઍ</button>
                <button class="btn">ઑ</button>
                <button class="btn">ૐ</button>
	        </div>
            <div class="row">
                <button class="btn">ઋ</button>
                <button class="btn">ૠ</button>
                <button class="btn">ં</button>
                <button class="btn">ઃ</button>
                <button class="btn">ઁ</button>
                <button class="btn">ા</button>
                <button class="btn">િ</button>
		        <button class="btn">ી</button>
                <button class="btn">ુ</button>
                <button class="btn">ૂ</button>
                <button class="btn">ે</button>
                <button class="btn">ૈ</button>
            </div>

            <div class="row">
                <button class="btn">ે</button>
                <button class="btn">ૈ</button>
                <button class="btn">ો</button>
                <button class="btn">ૌ</button>
                <button class="space">Space</button>
                <button class="btn">્</button>
                <button class="btn">્ર</button>
                <button class="btn">,</button>
                <button class="btn">.</button>
                
            </div>
            <div class="row">
                <button class="send" onclick="sendMessage()">Send</button>
                <button class="view" onclick="myFunction()">View Note</button>
            </div>
        </div>
    </div>
</body>
</html>