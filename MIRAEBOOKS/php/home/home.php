<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/home_slides_show.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/home.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/home_slideshow.js"></script>
</head>
<body onload="call_js()">
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if (isset($_GET['alert'])) {
                if ($_GET['alert'] == 'abnormal') {
                    echo "
                        <script>
                            alert('비정상적인 접근이 확인되었습니다');
                        </script>
                    ";
                } else if ($_GET['alert'] == 'admin') {
                    echo "
                        <script>
                            alert('관리자 페이지입니다');
                        </script>
                    ";
                }
            }
        ?>
    </header>
    <main>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/home/home_slide_show.php";
        ?>
        <div id="board_notice">
            <h2>NOTICE</h2>
            <table id="board_notice_table">
                <tr>
                    <th id="board_title">제목</th>
                    <th id="board_member_id">작성자</th>
                    <th id="board_register_date">작성일</th>
                </tr>
                <?php
                    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                    $sql_select_notice = "SELECT * FROM board WHERE board_type = 'notice' ORDER BY board_id DESC LIMIT 5";
                    $record_set = mysqli_query($con, $sql_select_notice);
                    while ($row = mysqli_fetch_array($record_set)) {
                        $board_id = $row['board_id'];
                        $board_title = $row['board_title'];
                        $board_register_date = $row['board_register_date'];
                        $board_register_date = substr($board_register_date, 0, 10);

                        $sql_count_notice_reply = "SELECT COUNT(*) FROM board_reply WHERE reply_post = $board_id";
                        $record_set_count = mysqli_query($con, $sql_count_notice_reply);
                        $row_count = mysqli_fetch_array($record_set_count);
                        $reply_count = $row_count['COUNT(*)'];
                ?>
                <tr>
                    <td>
                        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=notice&board_id=<?=$board_id?>&page=1">
                            <?=$board_title?>
                        </a><span id="reply_count">
                        [<?=$reply_count?>]</span>
                    </td>
                    <td>관리자</td>
                    <td><?=$board_register_date?></td>
                </tr>
                <?php        
                    }
                ?>
            </table>
        </div>
        <div id="board_free">
            <h2>RECENT POST</h4>
            <table id="board_free_table">
                <tr>
                    <th id="board_title">제목</th>
                    <th id="board_member_id">작성자</th>
                    <th id="board_register_date">작성일</th>
                </tr>
                <?php
                    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                    $sql_select_notice = "SELECT * FROM board WHERE board_type = 'free' ORDER BY board_id DESC LIMIT 5";
                    $record_set = mysqli_query($con, $sql_select_notice);
                    while ($row = mysqli_fetch_array($record_set)) {
                        $board_id = $row['board_id'];
                        $board_member_id = $row['board_member_id'];
                        $board_title = $row['board_title'];
                        $board_register_date = $row['board_register_date'];
                        $board_register_date = substr($board_register_date, 0, 10);

                        $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$board_member_id'";
                        $record_get_name = mysqli_query($con, $sql_get_name);
                        $record_name = mysqli_fetch_array($record_get_name);
                        $member_name = $record_name['member_name'];

                        $sql_count_notice_reply = "SELECT COUNT(*) FROM board_reply WHERE reply_post = $board_id";
                        $record_set_count = mysqli_query($con, $sql_count_notice_reply);
                        $row_count = mysqli_fetch_array($record_set_count);
                        $reply_count = $row_count['COUNT(*)'];
                ?>
                <tr>
                    <td>
                        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=free&board_id=<?=$board_id?>&page=1">
                            <?=$board_title?><span id="reply_count">
                        [<?=$reply_count?>]</span>
                        </a>
                    </td>
                    <td><?=$member_name?>(<?=$board_member_id?>)</td>
                    <td><?=$board_register_date?></td>
                </tr>
                <?php        
                    }
                    mysqli_close($con);
                ?>
            </table>
        </div>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>