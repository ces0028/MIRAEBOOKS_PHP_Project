<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/login.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/login_js.js"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            $member_id = $member_name = "";
            if (isset($_POST['member_id']) && !empty($_POST['member_id']) && isset($_POST['member_name']) && !empty($_POST['member_name'])) {
                $member_id = $_POST['member_id'];
                $member_name = $_POST['member_name'];
            }
        ?>
    </header>
    <main>
<?php
        if ($member_id && $member_name) {
    ?> 
    <div id="found_id">
        <h2>F&nbsp;I&nbsp;N&nbsp;D&nbsp;&nbsp;&nbsp;I&nbsp;D</h2>
        <p><?=$member_name?> 님의 아이디는<br>
        <a href="javascript:copy_id('<?=$member_id?>')" id="copy_id"><b><?=$member_id?></b></a> 입니다.</p>
        <ul id="find_id_pw">
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" id="find_id_pw_login">로그인</a></li>
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" id="find_pw">비밀번호 찾기</a></li>
        </ul>
    </div>
    <?php
        } else {
    ?>
     <form name="find_id_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=find_id" method="post" id="find_id_form">
        <h2>F&nbsp;I&nbsp;N&nbsp;D&nbsp;&nbsp;&nbsp;I&nbsp;D</h2>
        <?php
            if (isset($_POST['error'])) {
                echo "<p class='error active'> {$_POST['error']} </p>";
            } else {
                echo "<p class='error'></p>";
            }
        ?>
        <h4>이름</h4>
        <?php
            if (isset($_POST['member_name'])) {
                echo "<input type='text' name='member_name' id='member_name' placeholder='이름' value={$_POST['member_name']}>";
            } else {
                echo '<input type="text" name="member_name" id="member_name" placeholder="이름">';
            }
        ?>

        <h4>이메일</h4>
        <?php
            if (isset($_POST['member_email'])) {
                echo "<input type='email' name='member_email' id='member_email' placeholder='이메일' value={$_POST['member_name']}>";
            } else {
                echo '<input type="email" name="member_email" id="member_email" placeholder="이메일">';
            }
        ?>

        <h4>전화번호</h4>
        <?php
            if (isset($_POST['member_tel'])) {
                echo "<input type='tel' name='member_tel' id='member_tel' placeholder='아이디' value={$_POST['member_name']}>";
            } else {
                echo '<input type="tel" name="member_tel" id="member_tel" placeholder="아이디">';
            }
        ?>
        
        <input type="button" id="find_id_button" onclick="checkFindId()" value="아이디 찾기"></input>
        <ul id="find_id_pw">
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" id="find_id_pw_login">로그인</a></li>
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" id="find_pw">비밀번호 찾기</a></li>
        </ul>
    </form>
    <?php
        }
    ?>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>