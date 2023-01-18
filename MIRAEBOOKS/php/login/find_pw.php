<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/login.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/login_js.js?after"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            $success = $member_id = "";
            if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['member_id']) && !empty($_GET['member_id'])) {
                $success = '비밀번호를 새로 설정해주세요';
                $member_id = $_GET['member_id'];
            } else if (isset($_GET['success']) && $_GET['success'] == "2") {
                $success = '비밀번호가 성공적으로 변경되었습니다';
            }
        ?>
    </header>
    <main>
        <?php
            if ($success) {
        ?>
        <form name="set_new_pw_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=member_update_password" method="post" id="set_new_pw_form">
            <h2>F&nbsp;I&nbsp;N&nbsp;D&nbsp;&nbsp;&nbsp;P&nbsp;A&nbsp;S&nbsp;S&nbsp;W&nbsp;O&nbsp;R&nbsp;D</h2>
            <?php
                if (isset($_POST['error'])) {
                    echo "<p class='error active'> {$_POST['error']} </p>";
                } else {
                    echo "<p class='error'></p>";
                }

                if ($success) {
                    echo "<p class='success active'>".$success."</p>";
                }
            ?>
        
            <h4>아이디</h4>
            <input type="text" name="member_id" id="member_id" placeholder="아이디" value="<?=$member_id?>" readonly>

            <h4>비밀번호</h4>
            <input input type="password" name="member_password" id="member_password" placeholder="비밀번호">

            <h4>비밀번호 (확인)</h4>
            <input type="password" name="member_password_confirm" id="member_password_confirm" placeholder="비밀번호 (확인)">

            <input type="button" id="find_pw_button" onclick="checkSetNewPw()" value="확인"></input>
            <ul id="find_id_pw">
                <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" id="find_id_pw_login">로그인</a></li>
                <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_id.php" id="find_id">아이디 찾기</a></li>
            </ul>
        </form>
        <?php
            } else {
        ?>
        <form name="find_pw_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=find_pw" method="post" id="find_pw_form">
            <h2>F&nbsp;I&nbsp;N&nbsp;D&nbsp;&nbsp;&nbsp;P&nbsp;A&nbsp;S&nbsp;S&nbsp;W&nbsp;O&nbsp;R&nbsp;D</h2>
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
                    echo "<input type='text' name='member_id' id='member_id' placeholder='아이디' value={$_POST['member_id']}>";
                } else {
                    echo '<input type="text" name="member_id" id="member_id" placeholder="아이디">';
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

            <input type="button" id="find_pw_button" onclick="checkFindPw()" value="비밀번호 찾기"></input>
            <ul id="find_id_pw">
                <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" id="find_id_pw_login">로그인</a></li>
                <li><a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_id.php" id="find_id">아이디 찾기</a></li>
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