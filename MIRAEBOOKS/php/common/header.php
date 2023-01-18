<!-- Google Fonts & font awesome -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+KR:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9dc02b3074.js" defer crossorigin="anonymous"></script>

<?php
    session_start();
    $user_id = $user_name = $user_level = "";
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])&& isset($_SESSION['user_level'])) {
        $user_id = $_SESSION["user_id"];
        $user_name = $_SESSION["user_name"];
        $user_level = $_SESSION["user_level"];
    }
?>
<div id="header_container">
    <div id="header_upper">
        <ul id="menu_bar_upper">
            <?php
                if ($user_id) {
            ?>
            <li id="user_id">
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/mypage_edit.php"><?=$user_name?>&nbsp;&nbsp;(<?=$user_id?>)</a>
                
            </li>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/logout.php">LOGOUT</a>
            </li>
            <?php
                } else {
            ?>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php">LOGIN</a>
            </li>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/register.php">SIGN UP</a>
            </li>
            <?php
                }
            ?>
        </ul>
    </div>
    <div id="header_under">
        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/home/home.php" id="header_logo">MIRAEBOOKS</a>
        <ul id="menu_bar">
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/home/aboutus.php">ABOUT US</a>
            </li>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/books/books_list.php?lang=en&direction=DESC">BOOKS</a>
            </li>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_free.php">BOARD</a>
            </li>
            <?php
                if ($user_id) {
            ?>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/message/message_box.php?mode=receive">MESSAGE</a>
            </li>
            <?php
            } 
            ?>
            <?php
                if ($user_level == "999") {
            ?>
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/admin/admin_member.php">ADMIN</a>
            </li>
            <?php
                }
            ?>
        </ul>
    </div>
</div>
