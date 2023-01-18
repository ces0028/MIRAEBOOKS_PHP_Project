<?php
    // 데이터베이스 연결
    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
    // 세션을 사용하기 위해서 선언
    session_start();
    // GET 방식으로 mode값을 받아서 그에 따라 실행한 구문을 달리함
    if (!isset($_GET['mode'])) {
        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
        exit();
    } else {
        $mode = $_GET['mode'];
        switch($mode) {
            // E-B00K을 열람할 때 사용
            case 'ebook_view' :
                if (isset($_GET['ebook_id'])) {
                    $ebook_id = $_GET['ebook_id'];
                    $sql_select_ebook = "SELECT * FROM ebook WHERE ebook_id = $ebook_id";
                    $record_set = mysqli_query($con, $sql_select_ebook);
                    $row = mysqli_fetch_array($record_set);
                    $ebook_content_name = $row['ebook_content_name'];
                    $ebook_content_path = $row['ebook_content_path'];
                    $ebook_hit = $row['ebook_hit'];

                    $new_hit = $ebook_hit + 1;
                    $sql_update_hit = "UPDATE ebook SET ebook_hit = $new_hit WHERE ebook_id = $ebook_id";
                    $result = mysqli_query($con, $sql_update_hit);

                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].$ebook_content_path.$ebook_content_name);
                    } else {
                        echo "
                            <script>
                                alert('조회수를 갱신하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit();
                    }
                }
                break;
            // E-BOOK PDF파일을 다운로드 할 때 사용
            case 'ebook_download' :
                if (isset($_GET['ebook_id'])) {
                    $ebook_id = $_GET['ebook_id'];
                    $sql_select_ebook = "SELECT * FROM ebook WHERE ebook_id = $ebook_id";
                    $record_set = mysqli_query($con, $sql_select_ebook);
                    $row = mysqli_fetch_array($record_set);
                    $ebook_file_name = $row['ebook_file_name'];
                    $ebook_file_path = $_SERVER['DOCUMENT_ROOT'].$row['ebook_file_path'].$ebook_file_name;
                    $ebook_hit = $row['ebook_hit'];

                    $new_hit = $ebook_hit + 1;
                    $sql_update_hit = "UPDATE ebook SET ebook_hit = $new_hit WHERE ebook_id = $ebook_id";
                    $result = mysqli_query($con, $sql_update_hit);

                    if ($result) {
                        Header("content-type: application/octetstream");
                        header("Content-disposition: attachment; filename=".$ebook_file_name);
                        header("content-length: ".filesize($ebook_file_path));
                        header('Content-Transfer-Encoding: binary');
                        ob_clean();
                        readfile("$ebook_file_path");
                        exit;
                    } else {
                        echo "
                            <script>
                                alert('조회수를 갱신하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit;
                    }
                }
                break;
            // 추가로 E-BOOK을 등록할 때 사용
            case 'ebook_insert' :
                if ($_SESSION['user_level'] != 999) {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                    exit();
                }

                if (isset($_POST['ebook_lang']) && isset($_POST['ebook_title']) && isset($_POST['ebook_author']) && isset($_FILES['ebook_bookcover']) && isset($_FILES['ebook_content']) && isset($_FILES['ebook_file'])) {
                    echo "1";
                    $ebook_lang = mysqli_real_escape_string($con, $_POST['ebook_lang']);
                    $ebook_title = mysqli_real_escape_string($con, $_POST['ebook_title']);
                    $ebook_author = mysqli_real_escape_string($con, $_POST['ebook_author']);
                    
                    // 표지 이미지 파일을 저장
                    $ebook_bookcover_path     = '/MIRAEBOOKS/source/book_cover/';
                    $ebook_bookcover_name     = $_FILES['ebook_bookcover']['name'];
                    $ebook_bookcover_size     = $_FILES['ebook_bookcover']['size'];
                    $ebook_bookcover_tmp_name = $_FILES['ebook_bookcover']['tmp_name'];
                    $ebook_bookcover_error    = $_FILES['ebook_bookcover']['error'];

                    if (!$ebook_bookcover_error) {
                        $ebook_bookcover_uploaded_file = $_SERVER['DOCUMENT_ROOT'].$ebook_bookcover_path.$ebook_bookcover_name;

                        if ($ebook_bookcover_size > 300 * (1_000_000)) {
                            echo "
                                <script>
                                    alert('표지 파일 크기가 지정된 용량(300MB)을 초과합니다!<br>파일 크기를 체크해주세요!');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }

                        if (!move_uploaded_file($ebook_bookcover_tmp_name, $ebook_bookcover_uploaded_file)) {
                            echo "
                                <script>
                                    alert('표지 파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }
                    }

                    // HTML 파일을 저장
                    $ebook_content_path     = '/MIRAEBOOKS/source/html/';
                    $ebook_content_name     = $_FILES['ebook_content']['name'];
                    $ebook_content_size     = $_FILES['ebook_content']['size'];
                    $ebook_content_tmp_name = $_FILES['ebook_content']['tmp_name'];
                    $ebook_content_error    = $_FILES['ebook_content']['error'];

                    if (!$ebook_content_error) {
                        $ebook_content_uploaded_file = $_SERVER['DOCUMENT_ROOT'].$ebook_content_path.$ebook_content_name;

                        if ($ebook_content_size > 300 * (1_000_000)) {
                            echo "
                                <script>
                                    alert('링크 파일 크기가 지정된 용량(300MB)을 초과합니다!<br>파일 크기를 체크해주세요!');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }

                        if (!move_uploaded_file($ebook_content_tmp_name, $ebook_content_uploaded_file)) {
                            echo "
                                <script>
                                    alert('링크 파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }
                    }

                    // PDF 파일을 저장
                    $ebook_file_path     = '/MIRAEBOOKS/source/pdf/';
                    $ebook_file_name     = $_FILES['ebook_file']['name'];
                    $ebook_file_size     = $_FILES['ebook_file']['size'];
                    $ebook_file_tmp_name = $_FILES['ebook_file']['tmp_name'];
                    $ebook_file_error    = $_FILES['ebook_file']['error'];

                    if (!$ebook_file_error) {
                        $ebook_file_uploaded_file = $_SERVER['DOCUMENT_ROOT'].$ebook_file_path.$ebook_file_name;

                        if ($ebook_file_size > 300 * (1_000_000)) {
                            echo "
                                <script>
                                    alert('PDF 파일 크기가 지정된 용량(300MB)을 초과합니다!<br>파일 크기를 체크해주세요!');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }
                        if (!move_uploaded_file($ebook_file_tmp_name, $ebook_file_uploaded_file)) {
                            echo "
                                <script>
                                    alert('PDF 파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                                    history.go(-1)
                                </script>
                            ";
                            exit();
                        }
                    }

                    $sql_insert_ebook = "INSERT INTO ebook (ebook_lang, ebook_title, ebook_author, ebook_bookcover_name, ebook_bookcover_path, ebook_content_name, ebook_content_path, ebook_file_name, ebook_file_path) VALUES ('$ebook_lang', '$ebook_title', '$ebook_author', '$ebook_bookcover_name', '$ebook_bookcover_path', '$ebook_content_name', '$ebook_content_path', '$ebook_file_name', '$ebook_file_path')";
                    $result = mysqli_query($con, $sql_insert_ebook);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_books.php?sort=0&direction=ASC');
                    } else {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/ebook/ebook_insert.php?error=이북을 등록하는데 실패했습니다');
                    }
                    mysqli_close($con);
                } else {
                    echo "
                        <script>
                            alert('오류가 발생했습니다');
                            history.go(-1)
                        </script>
                    ";
                    exit();
                }
                break;
            // E-BOOK 공개 여부를 변경할 때 사용
            case 'ebook_update_open' :
                if (isset($_GET['ebook_id']) && isset($_GET['page']) && isset($_SESSION['user_level'])) {
                    if ($_SESSION['user_level'] != 999) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                        exit();
                    }
                    $ebook_id = $_GET['ebook_id'];
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $sql_select_ebook = "SELECT * FROM ebook WHERE ebook_id = $ebook_id";
                    $record_set = mysqli_query($con, $sql_select_ebook);
                    $row = mysqli_fetch_array($record_set);
                    $ebook_open = $row['ebook_open'];

                    $new_open = ($ebook_open == 0) ? 1 : 0;

                    $sql_update_open = "UPDATE ebook SET ebook_open = $new_open WHERE ebook_id = $ebook_id";
                    $result = mysqli_query($con, $sql_update_open);

                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_books.php?page='.$page.'');
                    } else {
                        echo "
                            <script>
                                alert('E-BOOK 공개 여부를 변경하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit;
                    }
                }
                break;
            // 체크박스를 사용해서 동시에 여러 개의 E-BOOK 공개 여부를 변경할 때 사용
            case 'multiple_update_open' :
                if (isset($_GET['page']) && isset($_SESSION['user_level']) && (isset($_POST['checkbox']))) {
                    $page = $_GET['page'];
                    if ($_SESSION['user_level'] != 999) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                        exit();
                    }
                    for ($i = 0; $i < count($_POST['checkbox']); $i++) {
                        $ebook_id = $_POST['checkbox'][$i];
                        $sql_select_ebook = "SELECT * FROM ebook WHERE ebook_id = $ebook_id";
                        $record_set = mysqli_query($con, $sql_select_ebook);
                        $row = mysqli_fetch_array($record_set);
                        $ebook_open = $row['ebook_open'];
                        $new_open = ($ebook_open == 0) ? 1 : 0;
                        $sql_update_open = "UPDATE ebook SET ebook_open = $new_open WHERE ebook_id = $ebook_id";
                        $result = mysqli_query($con, $sql_update_open);
                    }
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/admin/admin_books.php?page='.$page.'');
                    } else {
                        echo "
                            <script>
                                alert('E-BOOK 공개 여부를 변경하는 과정에서 오류가 발생했습니다');
                                history.go(-1)
                            </script>
                        ";
                        exit;
                    }
                } else {
                    echo "
                        <script>
                            alert('변경할 서적을 선택해주세요')
                            history.go(-1);
                        </script>
                    ";
                }
                break;
        }
    }
?>