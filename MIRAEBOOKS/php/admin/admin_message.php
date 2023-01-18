<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/admin.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/admin_js.js"></script>
</head>
<body>
    <header>
        <?php
            // HEADER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            // 관리자 페이지이기 때문에 페이지에 접속하고자 하는 사람이 관리자인지를 확인
            // 관리자가 아닐 경우에는 HOME 화면으로 이동시킴
            if ($user_level != 999) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                exit();
            }

            // GET방식을 통해 page 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            // 한 페이지에 몇 개의 게시물이 출력될 건지를 지정
            $scale = 10;
            // 쿼리문에서 몇 번째 값부터 가져올지를 지정
            $start = ($page - 1) * $scale;
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_admin.php";
        ?>
        <section>
            <div id="main_header">
                <h2>ADMIN > GROUP MESSAGE</h2>
            </div>
            <form name="board_manage_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/message_server.php?mode=admin_delete" method="post">
                <table id="group_message_table">
                    <tr>
                        <!-- 동시에 여러 개의 값을 선택하기 위해서 checkbox 생성 -->
                        <th id="select"><input type="checkbox" id="select_button" onclick="checkALL()"></th>
                        <th id="number">번호</th>
                        <th id="message_title">제목</th>
                        <th id="message_receive_id">받은 사람</th>
                        <th id="message_register_date">작성일</th>
                    </tr>
                    <?php
                        // 데이터베이스 연결
                        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                        // 전체 게시물 갯수를 가져옴
                        // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                        // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                        $sql_get_size = "SELECT COUNT(*) FROM message WHERE message_send_id = 'ADMIN'";
                        $record_set_count = mysqli_query($con, $sql_get_size);
                        $count_row = mysqli_fetch_array($record_set_count);
                        $count = $count_row['COUNT(*)'];
                        // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                        $total_page = ceil($count / $scale);
                        
                        // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                        $sql_select_message = "SELECT * FROM message WHERE message_send_id = 'ADMIN' ORDER BY message_id DESC LIMIT $start, $scale";
                        $record_set = mysqli_query($con, $sql_select_message);
                        $total_record = mysqli_num_rows($record_set);
                        
                        // 만약 등록된 메시지 없다면 대신 발송한 문자가 없다는 문구를 출력
                        if ($total_record == 0) {
                            echo "
                                <tr>
                                    <td colspan='5'>발송한 문자가 없습니다</td>
                                </tr>
                            ";
                        } else {
                            $number = $count - $start;
                            // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                            // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                            while($row = mysqli_fetch_array($record_set)) {
                                $message_id = $row['message_id'];
                                $message_receive_id = $row['message_receive_id'];
                                $message_title = $row['message_title'];
                                $message_register_date = $row['message_register_date'];

                                // 함께 표시할 회원 이름을 가져옴
                                $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$message_receive_id'";
                                $record_get_name = mysqli_query($con, $sql_get_name);
                                if (mysqli_num_rows($record_get_name) < 1) {
                                    $member_name = '알 수 없음';
                                } else {
                                    $record_name = mysqli_fetch_array($record_get_name);
                                    $member_name = $record_name['member_name'];
                                }
                        ?>
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" id="checkbox" value="<?=$message_id?>"></td>
                            <td><?=$number?></td>
                            <!-- 클릭을 하면 해당 메시지의 내용이 담긴 페이지로 이동하기 위한 앵커 -->
                            <td><a href="http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/message/message_view.php?mode=admin&message_id=<?=$message_id?>"><?=$message_title?></a></td>
                            <td><?=$member_name?>(<?=$message_receive_id?>)</td>
                            <td><?=$message_register_date?></td>
                        </tr>
                        <?php
                                $number--;
                            }
                            mysqli_close($con);
                        }
                        ?>
                    </table>
                    <button type="submit" id="delete_button">삭제</button>
                </form>
            <ul id="page_num">
            <?php
                // 함수를 사용하여 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_message.php?page=$page";
                get_paging($scale, $page, $total_page, $url);
            ?>
            </ul>
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