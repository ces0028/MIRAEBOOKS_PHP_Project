<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/message.css">
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }

            if (isset($_GET['mode']) || !empty($_GET['mode'])) {
                $mode = $_GET['mode'];
                $title = ($mode == 'send') ? 'SENT BOX' : 'INBOX';
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
            
            $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
            $scale = 10;
            $start = ($page - 1) * $scale;

            $search_scope = $search_keyword = "";
            if (isset($_POST['search_scope']) && !empty($_POST['search_scope']) && isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])) {
                $search_scope = $_POST['search_scope'];
                $search_keyword = $_POST['search_keyword'];
            }
        ?>
    </header>
    <main>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_message.php";
        ?>
        <section>
            <div id="main_header">
                <h2>MESSAGE > <?=$title?></h2>
            </div>
            <form action='http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/message/message_box.php?mode=<?=$mode?>&page=<?=$page?>' name='message_search_form' method="POST" id="search_box">
                <select name='search_scope' id="search_scope">
                <?php
                    echo $search_scope;
                    if ($search_scope == 'message_content') {
                ?>
                    <option value='message_title'>제목</option>
                    <option value='message_content' selected>내용</option>
                <?php
                        if ($mode == 'send') {
                            echo "<option value='message_receive_id'>받은 사람</option>";
                        } else {
                            echo "<option value='message_send_id'>보낸 사람</option>";
                        }

                    } else if ($search_scope == 'message_receive_id' || $search_scope == 'message_send_id'){
                    ?>
                    <option value='message_title'>제목</option>
                    <option value='message_content'>내용</option>
                <?php
                        if ($mode == 'send') {
                            echo "<option value='message_receive_id' selected>받은 사람</option>";       
                        } else {
                            echo "<option value='message_send_id' selected>보낸 사람</option>"; 
                        }
                    } else {
                ?>
                    <option value='message_title'>제목</option>
                    <option value='message_content'>내용</option>
                <?php
                        if ($mode == 'send') {
                            echo "<option value='message_receive_id'>받은 사람</option>";
                        } else {
                            echo "<option value='message_send_id'>보낸 사람</option>";
                        }
                    }

                    if ($search_keyword) {
                        echo "<input type='text' name='search_keyword' id='search_keyword' value=".$search_keyword.">";
                    } else {
                        echo "<input type='text' name='search_keyword' id='search_keyword'>";
                    }
                ?>
                <input type='submit' value='검색' id='search_button'>
                </select>
            </form>
            <table id="message_box">
                <tr>
                    <th id="number">번호</th>
                    <th id="message_title">제목</th>
                    <?php
                        if ($mode == 'send') {
                            echo "
                                <th id='message_member_id'>받은 사람</th>
                                <th id='register_date'>보낸 날짜</th>
                            ";
                        } else {
                            echo "
                                <th id='message_member_id'>보낸 사람</th>
                                <th id='register_date'>받은 날짜</th>
                            ";
                        }
                    ?>
                </tr>
            <?php
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                if ($search_scope && $search_keyword) {
                    $sql_get_size = ($mode == 'send') ? "SELECT COUNT(*) FROM message WHERE (message_send_id = '$user_id') AND ($search_scope LIKE '%".$search_keyword."%')" : "SELECT COUNT(*) FROM message WHERE (message_receive_id = '$user_id') AND ($search_scope LIKE '%".$search_keyword."%')";
                } else {
                    $sql_get_size = ($mode == 'send') ? "SELECT COUNT(*) FROM message WHERE message_send_id = '$user_id'" : "SELECT COUNT(*) FROM message WHERE message_receive_id = '$user_id'";
                }
                $record_set_count = mysqli_query($con, $sql_get_size);
                $count_row = mysqli_fetch_array($record_set_count);
                $count = $count_row['COUNT(*)'];
                $total_page = ceil($count / $scale);
                
                if ($search_scope && $search_keyword) {
                    $sql_select_message = ($mode == 'send') ? "SELECT * FROM message WHERE (message_send_id = '$user_id') AND ($search_scope LIKE '%".$search_keyword."%') ORDER BY message_id DESC LIMIT $start, $scale" : "SELECT * FROM message WHERE (message_receive_id = '$user_id') AND ($search_scope LIKE '%".$search_keyword."%') ORDER BY message_id DESC LIMIT $start, $scale";
                } else {
                    $sql_select_message = ($mode == 'send') ? "SELECT * FROM message WHERE message_send_id = '$user_id' ORDER BY message_id DESC LIMIT $start, $scale" : "SELECT * FROM message WHERE message_receive_id = '$user_id' ORDER BY message_id DESC LIMIT $start, $scale";
                }
                $record_set = mysqli_query($con, $sql_select_message);
                $total_record = mysqli_num_rows($record_set);

                if ($count == 0) {
                    if ($mode == 'send') {
                        if ($search_scope && $search_keyword) {
                            echo "<tr><td colspan='4' id='no'>검색 결과가 없습니다</td></tr>";
                        } else {
                            echo "<tr><td colspan='4' id='no'>보낸 쪽지가 없습니다</td></tr>";
                        }
                    } else {
                        if ($search_scope && $search_keyword) {
                            echo "<tr><td colspan='4' id='no'>검색 결과가 없습니다</td></tr>";
                        } else {
                            echo "<tr><td colspan='4' id='no'>받은 쪽지가 없습니다</td></tr>";
                        }
                    }
                } else {
                    $number = $count - $start;
                    while($row = mysqli_fetch_array($record_set)) {
                        $message_id = $row['message_id'];
                        $message_member_id = ($mode == 'send') ? $row['message_receive_id'] : $row['message_send_id'];
                        $message_title = $row['message_title'];
                        $message_content = $row['message_content'];
                        $message_register_date = $row['message_register_date'];
                        $message_register_date = substr($message_register_date, 0, 10);
                        if ($message_member_id == 'ADMIN') {
                            $message_member_name = '관리자';
                        } else {
                            $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$message_member_id'";
                            $record_get_name = mysqli_query($con, $sql_get_name);
                            if (mysqli_num_rows($record_get_name) == 1) {
                                $record_name = mysqli_fetch_array($record_get_name);
                                $message_member_name = $record_name['member_name'];
                            } else {
                                $message_member_name = '알 수 없음';
                            }
                        }
                ?>
                <tr>
                    <td id="number"><?=$number?></td>
                    <td><a href="http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/message/message_view.php?mode=<?=$mode?>&message_id=<?=$message_id?>"><?=$message_title?></a></td>
                    <td><?=$message_member_name?>(<?=$message_member_id?>)</td>
                    <td><?=$message_register_date?></td>
                </tr>
                <?php
                        $number--;
                    }
                    mysqli_close($con);
                }
                ?>
            </table>
            </div>
            <ul id="page_num">
            <?php
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/message/message_box.php?mode=$mode&page=$page";
                get_paging($scale, $page, $total_page, $url);
            ?>
            </ul>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>