<?php
    // 데이터베이스 연결
    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
    // GET 방식으로 mode값을 받아서 그에 따라 실행한 구문을 달리함
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
        switch($mode) {
            // 게시판에 게시물을 등록할 때 사용
            case 'board_insert' :
                if (isset($_GET['type']) && isset($_POST['member_id']) && (isset($_POST['board_title'])) && (isset($_POST['board_content']))) {
                    $board_type = $_GET['type'];
                    $board_member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    // 특수문자를 HTML로 변환
                    $board_title = htmlspecialchars($_POST['board_title'], ENT_QUOTES);
                    $board_content = htmlspecialchars($_POST['board_content'], ENT_QUOTES);
                    date_default_timezone_set('Asia/Seoul');
                    $board_register_date = date('Y-m-d H:i');

                    if ($board_type == 'qna') {
                        $sql_insert_board = "INSERT INTO qna_board_question (qna_member_id, qna_question_title, qna_question_content, qna_question_register_date) VALUES ('$board_member_id', '$board_title', '$board_content', '$board_register_date')";
                    } else {
                        $board_file_path = $_SERVER['DOCUMENT_ROOT'].'/MIRAEBOOKS/data/';
                        $board_file_name     = $_FILES['upload_file']['name'];
                        $upload_file_tmp_name = $_FILES['upload_file']['tmp_name'];
                        $board_file_type     = $_FILES['upload_file']['type'];
                        $upload_file_size     = $_FILES['upload_file']['size'];
                        $upload_file_error    = $_FILES['upload_file']['error'];

                        // 업로드된 파일명이 있고, 그 과정에서 오류가 발생하지 않았다면
                        if ($board_file_name && !$upload_file_error) {
                            // '.'을 기준으로 문자를 나눠서 파일명과 확장자를 구분함
                            $file = explode(".", $board_file_name);
                            $file_name =$file[0];
                            $file_ext = $file[1];
                            
                            // 파일명이 중복되는 경우를 방지하기 위해서 앞에 년, 월, 일, 시, 분, 초를 입력
                            $new_file_name = date('Y_m_d_H_i_s');
                            $new_file_name = $new_file_name."_".$file_name;
                            $board_file_saved_name = $new_file_name.".".$file_ext;
                            $uploaded_file = $board_file_path.$board_file_saved_name;

                            // 파일 사이즈가 300MB를 넘으면 경고창을 출력 후 뒤로 돌려보냄
                            if( $upfile_size  > 300_000_000 ) {
                                echo "
                                    <script>
                                        alert('업로드 파일 크기가 지정된 용량(300MB)을 초과합니다!<br>파일 크기를 체크해주세요!');
                                        history.go(-1)
                                    </script>
                                ";
                                exit();
                            }

                            // 임시저장소에서 지정된 저장소를 파일을 옮기는 데 오류가 발생하면 경고창 출력
                            if (!move_uploaded_file($upload_file_tmp_name, $uploaded_file)) {
                                echo "
                                    <script>
                                        alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                                        history.go(-1)
                                    </script>
                                ";
                                exit();
                            }
                        } else {
                            // 만약 파일을 첨부하지 않았을 때에는 빈 값이 입력되도록 함
                            $board_file_name = $board_file_type = $board_file_path = $board_file_saved_name = "";
                        }
                            
                        $sql_insert_board = "INSERT INTO board (board_type, board_member_id, board_title, board_content, board_register_date, board_file_name, board_file_type, board_file_path, board_file_saved_name) VALUES ('$board_type', '$board_member_id', '$board_title', '$board_content', '$board_register_date', '$board_file_name', '$board_file_type', '$board_file_path', '$board_file_saved_name')";
                    }
                    $result = mysqli_query($con, $sql_insert_board);

                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_'.$board_type.'.php');
                    } else {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_write.php?type='.$board_type.'&error=게시판에 게시물을 등록하는데 실패했습니다');
                    } 
                    mysqli_close($con);
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 첨부파일을 다운로드 할 때 사용
            case 'download' :
                $board_file_name = $board_file_type = $board_file_path = $board_file_saved_name = "";

                if (isset($_GET['board_file_name']) && isset($_GET['board_file_type']) && isset($_GET['board_file_path']) && isset($_GET['board_file_saved_name'])) {
                    $board_file_name = $_GET['board_file_name'];
                    $board_file_type = $_GET['board_file_type'];
                    $board_file_path = $_GET['board_file_path'];
                    $board_file_saved_name = $_GET['board_file_saved_name'];
                    $file_path = $board_file_path.$board_file_saved_name;
                }

                // 접속한 브라우저가 인터넷 익스플로우인지를 확인
                $ie = preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || 
                (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0') !== false && 
                strpos($_SERVER['HTTP_USER_AGENT'], 'rv:11.0') !== false);
                
                // 브라우저가 인터넷 익스플로우인 경우에는 첨부파일명이 한글이면 오류가 발생할 수 있기 때문에 이를 방어
                if ($ie){
                    $board_file_name = iconv('utf-8', 'euc-kr', $board_file_name);
                }

                // 첨부된 파일이 존재한다면 파일을 다운로드
                if (file_exists($file_path)) { 
                    $fp = fopen($file_path,"rb"); 
                    Header("Content-type: application/x-msdownload"); 
                    Header("Content-Length: ".filesize($file_path));     
                    Header("Content-Disposition: attachment; filename=".$board_file_name);   
                    Header("Content-Transfer-Encoding: binary"); 
                    Header("Content-Description: File Transfer"); 
                    Header("Expires: 0");       
                } 

                if (!fpassthru($fp)) { 
                    fclose($fp); 
                } else {
                    fclose($fp); 
                }
                break;
            // 요청게시판에 게시물을 등록할 때 사용
            case 'request_insert' :
                if (isset($_POST['request_member_id']) && isset($_POST['request_lang']) && isset($_POST['request_title']) && isset($_POST['request_author'])) {
                    $request_member_id = $_POST['request_member_id'];
                    $request_lang = $_POST['request_lang'];
                    // 특수문자를 HTML로 변환
                    $request_title = htmlspecialchars($_POST['request_title'], ENT_QUOTES);
                    $request_author = htmlspecialchars($_POST['request_author'], ENT_QUOTES);
                    date_default_timezone_set('Asia/Seoul');
                    $request_register_date = date('Y-m-d H:i');

                    $sql_insert_insert = "INSERT INTO request_board (request_member_id, request_lang, request_title, request_author, request_register_date) VALUES ('$request_member_id', '$request_lang', '$request_title', '$request_author', '$request_register_date')";
                    $result = mysqli_query($con, $sql_insert_insert);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_request.php');
                    } else {
                        echo "
                            <script>
                                alert('신청을 등록하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit();
                    }
                }
                break;
            // 문의게시판에 답변을 입력할 때 사용
            case 'answer_insert' :
                if (isset($_GET['page']) && isset($_POST['qna_id']) && isset($_POST['qna_answer'])) {
                    $page = $_GET['page'];
                    $qna_id = $_POST['qna_id'];
                    $qna_answer = htmlspecialchars($_POST['qna_answer'], ENT_QUOTES);
                    date_default_timezone_set('Asia/Seoul');
                    $qna_answer_register_date = date('Y-m-d H:i');

                    $sql_insert_answer = "INSERT INTO qna_board_answer (question_qna_id, qna_answer, qna_answer_register_date) VALUES ('$qna_id', '$qna_answer', '$qna_answer_register_date')";
                    $result = mysqli_query($con, $sql_insert_answer);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_board_qna.php?page='.$page.'');
                    } else {
                        echo "
                            <script>
                                alert('답변을 등록하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit();
                    }
                }
                break;
            // 댓글을 입력할 때 사용
            case 'reply_insert' :
                if (isset($_GET['type']) && isset($_GET['board_id']) && isset($_GET['page']) && isset($_POST['reply_post_member_id']) && isset($_POST['reply_member_id']) && isset($_POST['reply_content'])) {
                    $type = $_GET['type'];
                    $page = $_GET['page'];
                    $reply_post = $_GET['board_id'];
                    $reply_post_member_id = $_POST['reply_post_member_id'];
                    $reply_member_id = $_POST['reply_member_id'];
                    $reply_content = $_POST['reply_content'];
                    date_default_timezone_set('Asia/Seoul');
                    $reply_register_date = date('Y-m-d H:i');

                    $sql_insert_reply = "INSERT INTO board_reply (reply_post, reply_post_member_id, reply_member_id, reply_content, reply_register_date) VALUES ('$reply_post', '$reply_post_member_id', '$reply_member_id', '$reply_content', '$reply_register_date')";
                    $result = mysqli_query($con, $sql_insert_reply);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_view.php?type='.$type.'&board_id='.$reply_post.'&page='.$page);
                        exit();
                    } else {
                        echo "
                            <script>
                                alert('댓글을 등록하는 과정에서 오류가 발생했습니다.');
                                history.go(-1);
                            </script>
                            ";
                        exit();
                    }
                }
                break;
            // 회원이 본인의 게시물을 수정할 때 사용
            case 'member_update' :
                if (isset($_GET['type']) && isset($_GET['board_id']) && isset($_GET['page']) && isset($_POST['board_title']) && isset($_POST['board_content'])) {
                    $type = $_GET["type"];
                    $board_id = $_GET["board_id"];
                    $page = $_GET['page'];
                    $board_title = htmlspecialchars($_POST['board_title'], ENT_QUOTES);
                    $board_content = htmlspecialchars($_POST['board_content'], ENT_QUOTES);
                    
                    if ($_FILES["upload_file"]["name"]) {
                        if (isset($_POST['file_check']) && $_POST['file_check'] == 'on') {
                            $board_file_path = $_POST['board_file_path'];
                            $file_check = $_POST["file_check"];
                            $board_file_saved_name = $_POST['board_file_saved_name'];
                            if ($board_file_saved_name) {
                                $file_path = $board_file_path.$board_file_saved_name;
                                unlink($file_path);
                            }
                        } else if (isset($_POST['file_exist'])) {
                            echo "
                                <script>
                                    alert('파일을 새로 업로드할 경우에는 이전 파일을 삭제해주세요');
                                    history.go(-1);
                                </script>
                            ";
                            exit();
                        }
                        $board_file_path = $_SERVER['DOCUMENT_ROOT'].'/MIRAEBOOKS/data/';
                        $board_file_name = $_FILES["upload_file"]["name"];
                        $upfile_tmp_name = $_FILES["upload_file"]["tmp_name"];
                        $board_file_type = $_FILES["upload_file"]["type"];
                        $upfile_size     = $_FILES["upload_file"]["size"];
                        $upfile_error    = $_FILES["upload_file"]["error"];
            
                        if ($board_file_name && !$upfile_error) {
                            $file = explode(".", $board_file_name);
                            $file_name =$file[0];
                            $file_ext = $file[1];

                            date_default_timezone_set('Asia/Seoul');
                            $new_file_name = date('Y_m_d_H_i_s');
                            $new_file_name = $new_file_name."_".$file_name;
                            $board_file_saved_name = $new_file_name.".".$file_ext;
                            $uploaded_file = $board_file_path.$board_file_saved_name;
            
                            if( $upfile_size  >300_000_000 ) {
                                echo "
                                    <script>
                                        alert('업로드 파일 크기가 지정된 용량(1MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
                                        history.go(-1)
                                    </script>
                                ";
                                exit();
                            }

                            if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
                                echo "
                                    <script>
                                        alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                                        history.go(-1)
                                    </script>
                                ";
                                exit();
                            }
                        }
                    } else {
                        if ($_POST['file_check'] == 'on') {
                            $board_file_saved_name = $_POST['board_file_saved_name'];
                            if ($board_file_saved_name) {
                                $file_path = $board_file_path.$board_file_saved_name;
                                unlink($file_path);
                            }
                            $board_file_name = $board_file_type = $board_file_path = $board_file_saved_name = "";
                        } else {
                            $sql_update_board = "UPDATE board SET board_title='$board_title', board_content='$board_content' WHERE board_id = $board_id";
                            $result = mysqli_query($con, $sql_update_board);    
                            if ($result) {
                                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_view.php?type='.$type.'&board_id='.$board_id.'&page='.$page);
                                exit();
                            }
                            mysqli_close($con);    
                        }
                    }
                    $sql_update_board = "UPDATE board SET board_title='$board_title', board_content='$board_content', board_file_name = '$board_file_name', board_file_type = '$board_file_type', board_file_path = '$board_file_path', board_file_saved_name = '$board_file_saved_name' WHERE board_id = $board_id";
                    $result = mysqli_query($con, $sql_update_board);    
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_view.php?type='.$type.'&board_id='.$board_id.'&page='.$page);
                        exit();
                    }
                    mysqli_close($con);     
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 관리자가 요청게시판의 처리여부를 변경할 때 사용
            case 'update_request_result' :
                if (isset($_GET['request_id']) && isset($_GET['result']) && isset($_GET['page'])) {
                    $request_id = $_GET['request_id'];
                    $request_result = $_GET['result'];
                    $page = $_GET['page'];

                    $sql_update_request_result = "UPDATE request_board SET request_result = $request_result WHERE request_id = '$request_id'";
                    $result = mysqli_query($con, $sql_update_request_result);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_board_request.php?page='.$page.'');
                    } else {
                        echo "
                            <script>
                                alert('답변을 등록하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit();
                    }
                }
                break;
            // 회원이 본인이 작성한 게시물을 삭제할 때 사용
            case 'member_delete' : 
                if (isset($_GET['type']) && isset($_GET['board_id']) &&isset($_GET['page'])) {
                    $type = $_GET['type'];
                    $board_id = $_GET['board_id'];
                    $page = $_GET['page'];

                    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                    $sql_select_board = "SELECT * FROM board WHERE board_id = $board_id";
                    $record_set = mysqli_query($con, $sql_select_board);
                    $row = mysqli_fetch_array($record_set);
                    $board_file_path = $row["board_file_path"];
                    $board_file_saved_name = $row["board_file_saved_name"];
                
                    if ($board_file_saved_name) {
                        $file_path = $board_file_path.$board_file_saved_name;
                        unlink($file_path);
                    }
                
                    $sql_delete_board = "DELETE FROM board WHERE board_id = $board_id";
                    $result = mysqli_query($con, $sql_delete_board);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_'.$type.'.php?page='.$page.'');
                        exit();
                    }
                    mysqli_close($con);
                }
                break;
            // 댓글을 삭제할 때 사용
            case 'reply_delete' :
                if (isset($_GET['type']) && isset($_GET['board_id']) && isset($_GET['page']) && isset($_POST['reply_id'])) {
                    $type = $_GET['type'];
                    $page = $_GET['page'];
                    $reply_post = $_GET['board_id'];
                    $reply_id = $_POST['reply_id'];

                    $sql_delete_reply = "DELETE FROM board_reply WHERE reply_id = $reply_id";
                    $result = mysqli_query($con, $sql_delete_reply);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/board/board_view.php?type='.$type.'&board_id='.$reply_post.'&page='.$page);
                        exit();
                    } else {
                        echo "
                            <script>
                                alert('댓글을 삭제하는 과정에서 오류가 발생했습니다.');
                                history.go(-1);
                            </script>
                            ";
                        exit();
                    }
                }
                break;
            // 관리자가 회원이 작성한 게시물을 삭제할 때 사용
            case 'admin_delete' :
                session_start();
                if (isset($_SESSION['user_level'])) {
                    $user_level = $_SESSION['user_level'];
                    
                    if ($_SESSION['user_level'] != 999) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                        exit();
                    } 

                    if (isset($_POST['checkbox'])) {
                        for ($i = 0; $i < count($_POST['checkbox']); $i++) {
                            $board_id = $_POST['checkbox'][$i];
                            $sql_select_board = "SELECT * FROM board WHERE board_id = $board_id";
                            $record_set = mysqli_query($con, $sql_select_board);
                            $row = mysqli_fetch_array($record_set);
                            
                            $board_file_path = $row['board_file_path'];
                            $board_file_saved_name = $row['board_file_saved_name'];
                            if ($board_file_saved_name) {
                                $file_path = $board_file_path.$board_file_saved_name;
                                unlink($file_path);
                            }

                            $sql_delete_board = "DELETE FROM board WHERE board_id = $board_id";
                            $result = mysqli_query($con, $sql_delete_board);
                        }
                        if ($result) {
                                echo "
                                    <script>
                                        alert('게시물을 삭제했습니다')
                                        history.go(-1);
                                    </script>
                                ";
                            exit();
                            }
                    } else {
                        echo "
                            <script>
                                alert('삭제할 게시물을 선택해주세요')
                                history.go(-1);
                            </script>
                        ";
                        exit();
                    }
                }
                break;
        }
    } else {
        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
        exit();
    }
?>