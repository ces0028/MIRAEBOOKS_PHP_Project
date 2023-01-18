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

            if (isset($_GET['type'])) {
                $type = $_GET['type'];
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
            
            // 타입에 따라서 h2의 값을 달리함
            $title = ($type == 'notice') ? 'NOTICE BOARD' : (($type == 'qna') ? 'Q & A' : 'FREE BOARD');

            // 관리자 또는 회원에 한하여 접속 허가
            if ($type == 'notice') {
                if ($user_level != 999) {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                    exit();
                }
            } else{
                if (!$user_id) {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                    exit();
                }
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
                <h2>BOARD > <?=$title?> > POST</h2>
            </div>
            <?php
                if (isset($_GET['error'])) {
            echo "<p class='error active'> {$_GET['error']} </p>";
                } else {
            ?>
                <p class="error"></p>
            <?php
                }
            ?>
            <form name="board_write_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=board_insert&type=<?=$type?>" method="post" enctype="multipart/form-data">
                <table id="post_board">
                    <tr>
                        <th>작성자</th>
                        <td><input type="text" id="member_id" value="<?=$user_name?>(<?=$user_id?>)" readonly></td>
                        <input type="text" name="member_id" value="<?=$user_id?>" hidden>
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="board_title" id="board_title" maxlength="50"></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td><textarea name="board_content" id="board_content"></textarea></td>
                    </tr>
                <?php
                    // 자유게시판에 한하여 첨부파일 업로드를 활성화
                    if ($type == 'free') {
                ?>    
                    <tr>
                        <th>첨부파일</th>
                        <td><input type="file" name="upload_file" id="board_file"></td>
                    </tr>
                <?php
                    }
                ?>
                </table>
                <div id="buttons">
                    <!-- 서버로 데이터를 넘기기 전에 먼저 미입력된 값이 없는지를 자바스크립트로 확인 -->
                    <input type="button" id="post_button" onclick="checkInsertInput()" value="작성">
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