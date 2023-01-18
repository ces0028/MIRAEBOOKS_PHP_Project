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

            if (isset($_GET['type']) && isset($_GET['board_id']) && isset($_GET['page'])) {
                $type = $_GET['type'];
                $board_id = $_GET['board_id'];
                $page = $_GET['page'];

                $title = ($type == 'notice') ? 'NOTICE BOARD' : (($type == 'qna') ? 'Q & A' : (($type == 'free') ? 'FREE BOARD' : 'REQUEST BOARD'));

                if (isset($_GET['view'])) {
                    $view = $_GET['view'];
                } 
                
                // 회원 또는 관리자에 한하여 접속 허가
                if ($type == 'notice') {
                    if ($user_level != 999) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                        exit();
                    } 
                } else {
                    if (!$user_id) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                        exit();
                    }
                }
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
            // 데이터베이스 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS//db/db_create_statement.php";
            $sql_select_board = "SELECT * FROM board WHERE board_id = $board_id";
            $record_set = mysqli_query($con, $sql_select_board);
            
            if (mysqli_num_rows($record_set) == 1) {
                $row = mysqli_fetch_array($record_set);
                $board_member_id = $row["board_member_id"];
                $board_title = $row["board_title"];
                $board_content = $row["board_content"];
                $board_register_date = $row["board_register_date"];
                $board_hit = $row["board_hit"];
                $board_file_name = $row["board_file_name"];
                $board_file_type = $row["board_file_type"];
                $board_file_path = $row["board_file_path"];
                $board_file_saved_name = $row["board_file_saved_name"];
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_board.php";
        ?>
        <section id="board_update">
            <div id="main_header">
                <h2>BOARD > <?=$title?> > EDIT POST</h2>
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
            <form name="board_update_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=member_update&type=<?=$type?>&board_id=<?=$board_id?>&page=<?=$page?>" method="post" enctype="multipart/form-data" id="board_update_form">
                <table id="update_post">
                    <tr>
                        <th>작성자</th>
                        <td><input type="text" name="name" id="board_member_id" value="<?=$user_name?>(<?=$user_id?>)" readonly></td>
                        <input type="text" name="board_id" value="<?=$board_id?>" hidden>
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="board_title" id="board_title" maxlength="50" value="<?=$board_title?>"></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td><textarea name="board_content" id="board_content"><?=$board_content?></textarea></td>
                    </tr>
                    <tr>
                        <?php
                            if ($board_file_name) {
                        ?>
                        <th rowspan="2">첨부파일</th>
                        <td id="checkbox"><?=$board_file_name?> <input type="checkbox" name="file_check" id="file_check"> 삭제</td>
                        <input type="text" name="board_file_name" value="<?=$board_file_name?>" hidden>
                        <input type="text" name="board_file_type" value="<?=$board_file_type?>" hidden>
                        <input type="text" name="board_file_path" value="<?=$board_file_path?>" hidden>
                        <input type="text" name="board_file_saved_name" value="<?=$board_file_saved_name?>" hidden>
                        <input type="text" name="file_exist" value="exist" hidden>
                    </tr>
                    <tr>
                        <td><input type="file" name="upload_file" id="board_file"></td>
                    </tr>
                        <?php
                            } else {
                        ?>
                        <th>첨부파일</th>
                        <td><input type="file" name="upload_file" id="board_file"></td>
                    </tr>
                        <?php
                            }
                        ?>
                </table>
                <div id="buttons">
                    <input type="button" id="update_button" onclick="checkUpdatePost()" value="수정">
                <?php
                    if ($type == 'free') {
                        echo " <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_free.php?view=".$view."&page=".$page."'>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_".$type.".php?page=".$page."'>";
                    }
                ?>
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