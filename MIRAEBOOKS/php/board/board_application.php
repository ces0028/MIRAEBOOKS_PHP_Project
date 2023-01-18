<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board_post.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/board_js.js" defer></script>
</head>
<body>
    <header>
        <?php
            // HEADER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";
            
            // 회원에 한하여 접속 허가
            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_board.php";
        ?>
        <section>
            <div id="main_header">
                <h2>BOARD > REQUEST BOARD > APPLICATION</h2>
            </div>
            <?php
                // GET방식으로 전달되는 에러 메시지가 있는지에 따라서 출력을 달리함
                if (isset($_GET['error'])) {
            echo "<p class='error active'> {$_GET['error']} </p>";
                } else {
            ?>
                <p class="error"></p>
            <?php
                }
            ?>
            <form name="application" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=request_insert" method="post">
                <table id="application">
                    <tr>
                        <th>작성자</th>
                        <td><input type="text" id="request_member_id" value="<?=$user_name?>(<?=$user_id?>)" readonly></td>
                        <input type="text" name="request_member_id" value="<?=$user_id?>" hidden>
                    </tr>
                    <tr>
                        <th>언어</th>
                        <td>
                            <select name="request_lang" id="request_lang">
                                <option value="">선택</option>
                                <option value="en">영어</option>
                                <option value="jp">일본어</option>
                                <option value="kr">한국어</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="request_title" id="request_title" maxlength="100"></td>
                    </tr>
                    <tr>
                        <th>저자</th>
                        <td><input type="text" name="request_author" id="request_author" maxlength="100"></td>
                    </tr>
                </table>
                <div id="buttons">
                    <!-- 서버로 데이터를 넘기기 전에 먼저 자바스크립트로 해당 값을 체크 -->
                    <input type="button" id="apply_button" onclick="checkApplication()" value="신청">
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_<?=$type?>.php" id="buttona">
                        <input type="button" id="list_button" value="목록">
                    </a>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <?php
            // FOOTER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>