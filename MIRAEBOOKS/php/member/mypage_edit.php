<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/mypage.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/mypage_js.js"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";
        ?>
    </header>
    <main>
    <?php
        if (!$user_id) {
            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php');
            exit();
        }

        $member_password = $member_gender = $member_email = $member_tel =  "";
        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_mypage.php";
        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
        $sql_select_user = "SELECT * FROM members WHERE member_id = '$user_id'";
        $record_set = mysqli_query($con, $sql_select_user);
        $row = mysqli_fetch_array($record_set);

        $member_gender = $row['member_password'];
        $member_password = $row['member_password'];
        $member_gender = $row['member_gender'];
        $member_email = $row['member_email'];
        $member_tel = $row['member_tel'];
    ?>
            
        <section>
            <h2>E&nbsp;D&nbsp;I&nbsp;T&nbsp;&nbsp;&nbsp;I&nbsp;N&nbsp;F&nbsp;O&nbsp;R&nbsp;M&nbsp;A&nbsp;T&nbsp;I&nbsp;O&nbsp;N</h2>
            <form name="modify_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=member_update" method="post" id="modify_form">
                
                <?php
                    if (isset($_GET['success'])) {
                        echo "<p class='success active'>성공적으로 정보가 수정되었습니다</p>";
                    }
                    if (isset($_GET['error'])) {
                        echo "<p class='error'> {$_GET['error']} </p>";
                    } else {
                        echo "<p class='error'></p>";
                    }
                ?>

                <h4>아이디</h4>
                <input type="text" name="member_id" id="member_id" placeholder="아이디" value="<?=$user_id?>" readonly>

                <h4>비밀번호</h4>
                <input type="password" name="member_password" id="member_password" placeholder="비밀번호">

                <h4>비밀번호 (확인)</h4>
                <input type="password" name="member_password_confirm" id="member_password_confirm" placeholder="비밀번호 (확인)">

                <h4>이름</h4>
                <input type="text" name="member_name" id="member_name" placeholder="이름" value="<?=$user_name?>">

                <h4>성별</h4>
                <select name="member_gender" id="member_gender" value="<?=$member_gender?>">
                    <option value="">성별</option>
                <?php
                    if ($member_gender == '남성') {
                        echo "
                            <option value='M' selected>남성</option>
                            <option value='F'>여성</option>
                        ";
                    } else {
                        echo "
                            <option value='M'>남성</option>
                            <option value='F' selected>여성</option>
                        ";
                    }
                ?>
                </select>
            
                <h4>이메일</h4>
                <input type="text" name="member_email" id="member_email" placeholder="이메일" value="<?=$member_email?>">

                <h4>전화번호</h4>
                <input type="text" name="member_tel" id="member_tel" placeholder="전화번호" value="<?=$member_tel?>">

                <input type="reset" id="reset_button" value="초기화"></input>
                <input type="button" id="update_button" onclick="checkUpdateInfo()" value="수정"></input>
            </form>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>