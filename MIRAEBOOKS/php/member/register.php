<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/register.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/register_js.js"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";
        ?>
    </header>
    <main>
     <form name="register_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=member_insert" method="post" id="register_form">
        <h2>S&nbsp;I&nbsp;G&nbsp;N&nbsp;&nbsp;&nbsp;U&nbsp;P</h2>
        <?php
             if (isset($_GET['success'])) {
                echo "<p class='success'>성공적으로 가입되었습니다</p>";
            }
            if (isset($_POST['error'])) {
                echo "<p class='error active'> {$_POST['error']} </p>";
            } else {
                echo "<p class='error'></p>";
            }
        ?>

        <h4>아이디</h4>
        <?php
            if (isset($_POST['member_id'])) {
                echo "<input type='text' name='member_id' id='member_id' placeholder='아이디' value='".$_POST['member_id']."' maxlength='20'>";
            } else {
                echo '<input type="text" name="member_id" id="member_id" placeholder="아이디" maxlength="20">';
            }
        ?>

        <input type="button" class="button" id = "id_duplication_check" onclick="checkId()" value="중복확인"></input>

        <h4>비밀번호</h4>
        <input type="password" name="member_password" id="member_password" placeholder="비밀번호" maxlength="20">

        <h4>비밀번호 (확인)</h4>
        <input type="password" name="member_password_confirm" id="member_password_confirm" placeholder="비밀번호 (확인)" maxlength="20">

        <h4>이름</h4>
        <?php
            if (isset($_POST['member_name'])) {
                echo "<input type='text' name='member_name' id='member_name' placeholder='이름' value=;".$_POST['member_name']."' maxlength='20'>";
            } else {
                echo "<input type='text' name='member_name' id='member_name' placeholder='이름' maxlength='20'>";
            }
        ?>

        <h4>성별</h4>
        <select name="member_gender" id="member_gender">
            <option value="">성별</option>
        <?php
            if (isset($_POST['member_gender']) && isset($_POST['member_gender']) == 'M') {
                echo "
                    <option value='M' selected>남성</option>
                    <option value='F'>여성</option>
                ";
            } else if (isset($_POST['member_gender']) && isset($_POST['member_gender']) == 'F') {
                echo "
                    <option value='M'>남성</option>
                    <option value='F' selected>여성</option>
                ";
            } else {
                echo "
                    <option value='M'>남성</option>
                    <option value='F'>여성</option>
                ";
            }
        ?>
        </select>
    
        <h4>이메일</h4>
        <?php
            if (isset($_POST['member_email'])) {
                echo "<input type='email' name='member_email' id='member_email' placeholder='이메일' value='".$_POST['member_email']."'>";
            } else {
                echo '<input type="text" name="member_email" id="member_email" placeholder="이메일">';
            }
        ?>

        <h4>전화번호</h4>
        <?php
            if (isset($_POST['member_tel'])) {
                echo "<input type='tel' name='member_tel' id='member_tel' placeholder='전화번호' value='".$_POST['member_tel']."'>";
            } else {
                echo '<input type="text" name="member_tel" id="member_tel" placeholder="전화번호" maxlength=16>';
            }
        ?>
        <input type="reset" id="reset_button" value="초기화"></input>
        <input type="button" id="sign_up_button" onclick="checkInsertMember()" value="회원가입"></input>
    </form>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>