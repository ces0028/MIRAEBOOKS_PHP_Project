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

            if (isset($_GET['alert']) && $_GET['alert'] == 'login') {
                echo "
                    <script>
                        alert('로그인 후 이용 가능합니다');
                    </script>
                ";
            }
            $user_id = "";
            if (isset($_COOKIE['user_id'])) {
                $user_id = $_COOKIE['user_id'];
            }
        ?>
    </header>
    <main>
     <form name="login_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=login" method="post" id="login_form">
        <h2>L&nbsp;O&nbsp;G&nbsp;I&nbsp;N</h2>
        <?php
            if (isset($_POST['error'])) {
                echo "<p class='error active'> {$_POST['error']} </p>";
            } else {
                echo "<p class='error'></p>";
            }
        ?>
        
        <h4>아이디</h4>
        <?php
            if (isset($_POST['member_id'])) {
                echo "<input type='text' name='member_id' id='member_id' placeholder='아이디' value='".$_POST['member_id']."'>";
            } else if ($user_id) {
                echo "<input type='text' name='member_id' id='member_id' placeholder='아이디' value=".$user_id.">";
            } else {
                echo '<input type="text" name="member_id" id="member_id" placeholder="아이디">';
            }
        ?>

        <h4>비밀번호</h4>
        <input type="password" name="member_password" id="member_password" placeholder="비밀번호">
        
        <ul id="save_id_box">
            <?php
            if ($user_id) {
                echo " <input type='checkbox' name='save_id' id='save_id' checked>";
            } else {
                echo " <input type='checkbox' name='save_id' id='save_id'>";
            }
            ?>
            <span>아이디 저장</span>
        </ul>

        <input type="button" id="login_button" onclick="check_login()" value="LOGIN"></input>

        <ul id="find_id_pw">
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_id.php" id="find_id">아이디 찾기</a></li>
            <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" id="find_pw">패스워드 찾기</a></li>
        </ul>
    </form>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>