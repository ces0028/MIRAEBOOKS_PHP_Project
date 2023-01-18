<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board_view.css">
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

                $title = ($type == 'notice') ? 'NOTICE BOARD' : 'FREE BOARD';
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }

            if (isset($_GET['view'])) {
                $view = $_GET['view'];
            } 
            
            // 회원에 한하여 접속 허가
            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }
            
            // 데이터베이스 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
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

                $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$board_member_id'";
                $record_get_name = mysqli_query($con, $sql_get_name);
                if (mysqli_num_rows($record_get_name) < 1) {
                    $member_name = '알 수 없음';
                } else {
                    $record_name = mysqli_fetch_array($record_get_name);
                    $member_name = $record_name['member_name'];
                }

                $new_hit = $board_hit + 1;
                $sql_update_hit = "UPDATE board SET board_hit = $new_hit WHERE board_id = $board_id";
                $result = mysqli_query($con, $sql_update_hit);

                if (!$result) {
                    echo "
                        <script>
                            alert('조회수를 갱신하는 과정에서 발생했습니다');
                            history.go(-1);
                        </script>
                    ";
                exit();
                }
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
        <section>
            <div id="main_header">
                <h2>BOARD > <?=$title?> > <?=$board_title?></h2>
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
            <table id="post_view">
                <tr>
                    <th id="title">제목</th>
                    <td id="board_title"><?=$board_title?></td>
                <?php
                    if ($type == 'notice') {
                ?>
                    <td id="other">관리자&nbsp;&nbsp;|&nbsp;&nbsp;<?=$board_register_date?></td>
                <?php
                    } else {
                ?>
                    <td id="other"><?=$member_name?>(<?=$board_member_id?>)&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?=$board_register_date?></td>
                <?php
                    }
                ?>    
                </tr>
                <?php
                // 첨부파일이 있을 경우에 한하여 다운로드를 확성화
                if ($board_file_name) {
                    $file_path = $board_file_path.$board_file_saved_name;
                    $file_size = filesize($file_path);
                ?>
                <tr>
                    <th>첨부파일</th>
                    <td colspan='2' id="board_file">
                        <?=$board_file_name?> (<?=$file_size?> Byte)&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/board_server.php?&mode=download&board_file_name=<?=$board_file_name?>&board_file_type=<?=$board_file_type?>&board_file_path=<?=$board_file_path?>&board_file_saved_name=<?=$board_file_saved_name?>'><button type="button" id="save_button">저장</button></a>
                    </td>
                </tr>
                <?php
                    }
                ?>
                <tr>
                    <th>내용</th>
                    <td colspan='2' id="board_content">
                        <textarea readonly><?=$board_content?></textarea></td>
                </tr>
            </table>
            <div>
                <div id="comment_header">
                    <h3>COMMENT</h3>
                </div>
                <div id="reply_comment_container">
                <?php
                    // 해당 게시물에 달린 댓글을 모두 가져옴
                    $sql_select_ripple = "SELECT * from board_reply WHERE reply_post='$board_id' ";
                    $record_set = mysqli_query($con, $sql_select_ripple);
                    while ($row = mysqli_fetch_array($record_set)) {
                        $reply_id = $row['reply_id'];
                        $reply_post = $row['reply_post'];
                        $reply_post_member_id = $row['reply_post_member_id'];
                        $reply_member_id = $row['reply_member_id'];
                        $reply_content = $row['reply_content'];
                        $reply_register_date = $row['reply_register_date'];
                ?>  
                    <ul id="comment_content">
                    <?php
                        // 댓글을 작성한 사람이거나, 댓글이 달린 게시물의 작성자, 관리자면 댓글을 삭제 가능
                        if ($_SESSION['user_level'] == 999 || $user_id == $reply_post_member_id || $user_id == $reply_member_id) {
                    ?>
                        <form name="reply_delete_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=reply_delete&type=<?=$type?>&board_id=<?=$board_id?>&page=<?=$page?>" method="post" id="reply_delete_form">
                            <input type="hidden" name="reply_id" value="<?=$reply_id?>">
                            <li>
                                <span id="reply_member_id"><?= $reply_member_id?></span>
                                <span id="reply_register_date"><?=$reply_register_date?></span>
                                <input type="submit" id="reply_delete" value="삭제" >
                            </li>
                            <li id="reply_content_view"><?=$reply_content?></li>
                        </form>
                    <?php
                        } else {
                        ?>
                            <span id="reply_member_id"><?= $reply_member_id?></span>
                            <span id="reply_register_date"><?=$reply_register_date?></span>
                            <li id="reply_content_view"><?=$reply_content?></li>
                    <?php
                        }
                    ?>
                    </ul>
                    <?php
                }
                mysqli_close($con);
        ?>
                <form name="reply_write_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=reply_insert&type=<?=$type?>&board_id=<?=$board_id?>&page=<?=$page?>" method="post" id="reply_write_form">
                    <input type="hidden" name="reply_post_member_id" value="<?=$board_member_id?>">
                    <input type="hidden" name="reply_member_id" value="<?=$user_id?>">
                    <textarea name="reply_content" id="reply_content_insert"></textarea>
                    <button id="reply_button">입력</button>
                </form>
            </div>
            </div>
            <div id="buttons">
                <?php
                // 자유게시판에서는 누구나, 공지사항 게시판에서는 관리자만 글쓰기 작성
                if ($type == 'free' || ($type == 'notice' && $user_level == 999)) {
                ?>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_post.php?type=<?=$type?>" id="buttona">
                    <input type="button" id="post_button" value="글쓰기">
                </a>
                <?php
                }

                // 게시물을 작성한 회원이나, 관리자에 한하여 게시물 삭제 가능
                if ($user_id == $board_member_id || $user_level == 999) {
            ?>
                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=member_delete&type=<?=$type?>&board_id=<?=$board_id?>&page=<?=$page?>">
                    <input type="button" id="delete_button" value="삭제">
                </a>
                <?php
                    if ($type == 'notice' || $type = 'admin') {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_update.php?type=".$type."&board_id=".$board_id."&page=".$page."'>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_update.php?type=".$type."&board_id=".$board_id."&view=".$view."&page=".$page."'>";
                    }

                    if ($user_id == $board_member_id) {
                ?> 
                        <input type="button" id="update_button" value="수정">
                    </a>
                <?php
                    }
                }
                if ($type == 'free') {
                    echo " <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_free.php?view=".$view."&page=".$page."'>";
                } else if ($type == 'admin') {
                    echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_board_free.php?page=".$page."'>";
                } else {
                    echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_".$type.".php?page=".$page."'>";
                }
            ?> 
                    <input type="button" id="list_button" value="목록">
                </a>
            </div>
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