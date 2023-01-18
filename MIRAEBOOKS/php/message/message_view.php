<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/message.css?after">
</head>
<body onload="clickEvent()">
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }

            if (isset($_GET['mode']) && isset($_GET['message_id'])) {
                $mode = $_GET['mode'];
                $message_id = $_GET['message_id'];
                $title = ($mode == 'admin') ? 'GROUP MESSAGE MANAGEMENT' : (($mode == 'send') ? 'SENT BOX' : 'INBOX');
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
        ?>
    </header>
    <main>
        <?php
            if ($mode == 'admin') {
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_admin.php";
            } else {
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_message.php";
            }
        ?>
        <section>
            <?php
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                $sql_select_message = "SELECT * FROM message WHERE message_id = '$message_id'";
                $record_set = mysqli_query($con, $sql_select_message);
                $row = mysqli_fetch_array($record_set);
                $message_member_id = ($mode == 'send') ? $row['message_receive_id'] : $row['message_send_id'];
                $message_title = $row['message_title'];
                $message_content = $row['message_content'];
                $message_register_date = $row['message_register_date'];

                if ($message_member_id == 'ADMIN') {
                    $message_member_name = '관리자';
                } else {
                    $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$message_member_id'";
                    $record_get_name = mysqli_query($con, $sql_get_name);
                    $record_name = mysqli_fetch_array($record_get_name);
                    $message_member_name = $record_name['member_name'];
                }
                mysqli_close($con);
            ?>
            <div id="main_header">
                <h2>MESSAGE > <?=$title?> > <?=$message_title?></h2>
            </div>
            <table id="message_view">
                <tr>
                    <th id="title">제목</th>
                    <td id="message_title"><?=$message_title?></td>
                    <td id="other"><?=$message_member_name?>(<?=$message_member_id?>)&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?=$message_register_date?></td>
                </tr>
                <tr>
                    <th id="content">내용</th>
                    <td id="message_content" colspan="2">
                        <textarea readonly><?=$message_content?></textarea>
                    </td>
                </tr>
            </table>
            </div>
            <?php
                if ($mode == 'receive' && $message_member_id != 'ADMIN') {
                    echo "
                        <ul id='buttons'>
                            <li>
                                <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/message/message_send.php?type=reply&message_id=".$message_id."'>
                                    <input type='button' id='reply_button' value='답장'>
                                </a>
                            </li>
                            <li>
                                <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/server/message_server.php?mode=member_delete&message_id=".$message_id."'>
                                    <input type='button' id='delete_button' value='삭제'>
                                </a>
                            </li>
                        </ul>
                    ";
                } 

                if ($message_member_id == 'ADMIN' && $user_level == 999) {
                    echo "
                        <ul id='buttons'>
                            <li>
                                <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/server/message_server.php?mode=member_delete&message_id=".$message_id."'>
                                    <input type='button' id='delete_button' value='삭제'>
                                </a>
                            </li>
                        </ul>
                    ";
                }

            ?>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>