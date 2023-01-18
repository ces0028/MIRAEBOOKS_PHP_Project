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
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_mypage.php";
        ?>
        <section>
            <h2>M&nbsp;E&nbsp;M&nbsp;B&nbsp;E&nbsp;R&nbsp;S&nbsp;H&nbsp;I&nbsp;P&nbsp;&nbsp;&nbsp;W&nbsp;I&nbsp;T&nbsp;H&nbsp;D&nbsp;R&nbsp;A&nbsp;W&nbsp;A&nbsp;L</h2>
                <?php
                    if (isset($_POST['error'])) {
                        echo "<p class='error'>".$_POST['error']."</p>";
                    } else {
                        echo "<p class='error'></p>";
                    }
                    if (isset($_GET['success'])) {
                        echo "<p class='success active'>성공적으로 탈퇴되었습니다.<br>지금까지 MIRAEBOOKS를 이용해주셔서 감사합니다.</p>";
                        session_unset();
                    } else {
                        if (!$user_id) {
                            echo "
                                <script>
                                    alert('로그인 후 이용해주십시오')
                                    history.go(-1);
                                </script>
                            ";
                            exit();
                        }
                ?>
                <ul id="withdrawal_notice">
                    <li><h3 id="caution">CAUTION</h3></li>
                    <li id="notice_content">게시판에 등록한 게시물은 탈퇴 후에도 남아 있습니다.</li>
                    <li id="notice_content">글이 남아있는 것을 원치 않으신다면 삭제 후 탈퇴하시기 바랍니다.</li>
                    <li id="notice_content">탈퇴한 아이디로는 다시 가입할 수 없습니다. </li>
                    <li id="notice_content">또한, 탈퇴한 아이디 및 데이터는 복구할 수 없습니다.</li>
                </ul>

            <form name="withdrawal_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=member_withdrawal" method="post" id="withdrawal_form">
                <div id="checkbox">
                    <span>위 내용을 모두 확인하였습니다 </span>
                    <input type="checkbox" name="withdrawal_check" id="withdrawal_check">
                </div>

                <h4>아이디</h4>
                <input type="text" name="member_id" id="member_id" value="<?=$user_id?>" readonly>

                <h4>비밀번호</h4>
                <input type="password" name="member_password" id="member_password" placeholder="비밀번호">
                
                <input type="button" id="withdrawal_button" onclick="checkWithdrawal()" value="탈퇴"></input>
                <?php
                    }
                ?>
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