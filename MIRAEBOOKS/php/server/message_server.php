<?php
    // 데이터베이스 연결
    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
    // GET 방식으로 mode값을 받아서 그에 따라 실행한 구문을 달리함
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
        switch($mode) {
            // 문자를 전송할 때 사용
            case 'message_insert' :
                session_start();
                // 관리자가 전체 보내기를 체크했을 때
                if (isset($_POST['checkbox']) && $_POST['checkbox'] == 'on' && $_SESSION['user_level'] == 999 ) {
                    if (isset($_POST['message_title']) && isset($_POST['message_content'])) {
                        $message_send_id = 'ADMIN';
                        $admin_id = $_POST['message_send_id'];
                        $message_title = htmlspecialchars($_POST['message_title'], ENT_QUOTES);
                        $message_title = '[관리자] '.$message_title;
                        $message_content = htmlspecialchars($_POST['message_content'], ENT_QUOTES);
                        date_default_timezone_set('Asia/Seoul');
                        $message_register_date = date('Y-m-d H:i');

                        $sql_select_all_id = "SELECT * FROM members WHERE member_id != '$admin_id'";
                        $record_set = mysqli_query($con, $sql_select_all_id);
                        while ($row = mysqli_fetch_array($record_set)) {
                            $message_receive_id = $row['member_id'];
                            $sql_insert_message_all = "INSERT INTO message (message_send_id, message_receive_id, message_title, message_content, message_register_date) VALUES ('$message_send_id', '$message_receive_id', '$message_title', '$message_content', '$message_register_date')";
                            $result = mysqli_query($con, $sql_insert_message_all);
                        }
                        if (!$result) {
                            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                            exit();
                        } else {
                            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_message.php');
                        }
                    } else {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                        exit();
                    }
                // 관리자가 체크박스를 체크하지 않았을 때 OR 일반 회원
                } else {
                    if (isset($_POST['message_send_id']) && isset($_POST['message_receive_id']) && (isset($_POST['message_title'])) && (isset($_POST['message_content']))) {
                        $message_send_id = mysqli_real_escape_string($con, $_POST['message_send_id']);
                        $message_receive_id = mysqli_real_escape_string($con, $_POST['message_receive_id']);
                        $message_title = htmlspecialchars($_POST['message_title'], ENT_QUOTES);
                        $message_content = htmlspecialchars($_POST['message_content'], ENT_QUOTES);
                        date_default_timezone_set('Asia/Seoul');
                        $message_register_date = date('Y-m-d H:i');

                        $sql_check_id = "SELECT * FROM members WHERE member_id = '$message_receive_id'";
                        $record_set = mysqli_query($con, $sql_check_id);

                        if (mysqli_num_rows($record_set) != 1) {
                            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/message/message_send.php?error=수신 아이디를 다시 한 번 확인해주세요');
                        } else {
                            $sql_insert_message = "INSERT INTO message (message_send_id, message_receive_id, message_title, message_content, message_register_date) VALUES ('$message_send_id', '$message_receive_id', '$message_title', '$message_content', '$message_register_date')";
                            $result = mysqli_query($con, $sql_insert_message);
                            if ($result) {
                                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/message/message_box.php?mode=send');
                            } else {
                                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/message/message_send.php?error=메시지 발송하는 과정에서 오류가 발생했습니다');
                            }
                        }
                    } else {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                        exit();
                    }
                }
                break;
            // 문자를 수신한 회원이 문자를 삭제하고자 할 때
            case 'member_delete' :
                if (isset($_GET['message_id'])) {
                    $message_id = $_GET['message_id'];
                    $sql_delete_message = "DELETE FROM message WHERE message_id = '$message_id'";
                    $result = mysqli_query($con, $sql_delete_message);
                    if (!$result) {
                        echo "
                            <script>
                                alert('문자를 삭제하는 과정에서 오류가 발생했습니다')
                                history.go(-1);
                            </script>
                        ";
                        exit();
                    }
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/message/message_box.php?mode=receive');
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit(); 
                }
                break;
            // 관리자가 단체문자를 삭제할 때 사용
            case 'admin_delete' :
                session_start();
                if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 999) {
                    if (isset($_POST['checkbox'])) {
                        for ($i = 0; $i < count($_POST['checkbox']); $i++) {
                            $message_id = $_POST['checkbox'][$i];
                            $sql_delete_messages = "DELETE FROM message WHERE message_id = '$message_id'";
                            $result = mysqli_query($con, $sql_delete_messages);
                            if (!$result) {
                                echo "
                                    <script>
                                        alert('문자를 삭제하는 과정에서 오류가 발생했습니다')
                                        history.go(-1);
                                    </script>
                                ";
                                exit();
                            } else {
                                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_message.php');
                            }
                        }
                    } else {
                        echo "
                            <script>
                                alert('삭제할 게시물을 선택해주세요')
                                history.go(-1);
                            </script>
                        ";
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                    exit();
                }
                break;
        }
    } else {
        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
        exit();
    }
?>