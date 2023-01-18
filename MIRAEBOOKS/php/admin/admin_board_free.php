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

            // GET방식을 통해 page와 sort 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1과 3을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 3;
            
            // 클릭을 할 때마다 오름차순, 내림차순이 반복돼서 진행될 수 있게 함
            if (isset($_GET['direction'])) {
                $direction = $_GET['direction'];
                $direction = ($direction == 'DESC') ? 'ASC' : 'DESC';
            } else {
                $direction = 'DESC';
            }

            // 입력된 sort값에 따라서 쿼리문에 대입될 값을 부여함
            switch($sort) {
                case 1: 
                    $sort = 'board_title'; 
                    break;
                case 2: 
                    $sort = 'board_member_id'; 
                    break;
                case 3: 
                    $sort = 'board_register_date'; 
                    break;
                case 4: 
                    $sort = 'board_file_name'; 
                    break;
                case 5: 
                    $sort = 'board_hit'; 
                    break;
            }

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
                <h2>ADMIN > BOARD > FREE</h2>
            </div>
            <form name="board_management_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=admin_delete" method="post" enctype="multipart/form-data">
                <table id="free_board_table">
                    <tr>
                        <!-- 동시에 여러 개의 값을 선택하기 위해서 checkbox 생성 -->
                        <th id="select"><input type="checkbox" id="select_button" onclick="checkALL()"></th>
                        <th id="number">번호</th>
                        <!-- 제목줄을 클릭하면 값이 정렬이 되도록 앵커 생성 -->
                        <th id="board_title">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_board_free.php?sort=1&direction=<?=$direction?>'>제목</a>
                        </th>
                        <th id="board_member_id">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_board_free.php?sort=2&direction=<?=$direction?>'>작성자</a>
                        </th>
                        <th id="board_register_date">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_board_free.php?sort=3&direction=<?=$direction?>'>작성일</a>
                        </th>
                        <th id="board_file">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_board_free.php?sort=4&direction=<?=$direction?>'>파일</a>
                        </th>
                        <th id="board_hit">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_board_free.php?sort=5&direction=<?=$direction?>'>조회수</a>
                        </th>
                    </tr>
                    <?php
                        // 데이터베이스 연결
                        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                        // 전체 게시물 갯수를 가져옴
                        // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                        // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                        $sql_get_size = "SELECT COUNT(*) FROM board WHERE board_type = 'free'";
                        $record_set_count = mysqli_query($con, $sql_get_size);
                        $count_row = mysqli_fetch_array($record_set_count);
                        $count = $count_row['COUNT(*)'];
                        // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                        $total_page = ceil($count / $scale);
                        
                        // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                        $sql_select_board = "SELECT * FROM board  WHERE board_type = 'free' ORDER BY ".$sort." ".$direction." LIMIT $start, $scale";
                        $record_set = mysqli_query($con, $sql_select_board);
                        $total_record = mysqli_num_rows($record_set);
                        
                        // 만약 등록된 게시물이 없다면 대신 등록된 게시물이 없다는 문구를 출력
                        if ($total_record == 0) {
                            echo "
                                <tr>
                                    <td colspan='7'>등록된 게시물이 없습니다</td>
                                </tr>
                            ";
                        } else {
                            $number = $count - $start;
                            // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                            // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                            while($row = mysqli_fetch_array($record_set)) {
                                $board_id = $row['board_id'];
                                $board_member_id = $row['board_member_id'];
                                $board_title = $row['board_title'];
                                $board_register_date = $row['board_register_date'];
                                $board_hit = $row['board_hit'];
                                $board_file_name = $row['board_file_name'];
                                $board_file_type = $row['board_file_type'];
                                $board_file_path = $row['board_file_path'];
                                $board_file_saved_name = $row['board_file_saved_name'];
                                
                                // board_member_id와 함께 출력할 회원 이름을 가져옴
                                $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$board_member_id'";
                                $record_get_name = mysqli_query($con, $sql_get_name);
                                if (mysqli_num_rows($record_get_name) < 1) {
                                    $member_name = '알 수 없음';
                                } else {
                                    $record_name = mysqli_fetch_array($record_get_name);
                                    $member_name = $record_name['member_name'];
                                }

                                // board_title와 함께 출력할 게시물에 달린 댓글의 갯수를 가져옴
                                $select_reply_count = "SELECT COUNT(*) FROM board_reply WHERE reply_post = $board_id";
                                $record_get_reply_count = mysqli_query($con, $select_reply_count);
                                $record_count = mysqli_fetch_array($record_get_reply_count);
                                $reply_count = $record_count['COUNT(*)'];
                        ?>
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" id="checkbox" value="<?=$board_id?>"></td>
                            <td><?=$number?></td>
                            <td>
                                <!-- 클릭을 하면 해당 게시물의 내용이 담긴 페이지로 이동하기 위한 앵커 -->
                                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=admin&board_id=<?=$board_id?>&page=<?=$page?>"><?=$board_title?><span id="reply_count">[<?=$reply_count?>]</span></a>
                            </td>
                            <td><?=$member_name?>(<?=$board_member_id?>)</td>
                            <td><?=$board_register_date?></td>
                        <?php
                            // 만약 첨부파일이 있을 경우에는 파일 아이콘을, 없을 경우에는 0 아이콘을 출력
                            if ($board_file_name) {
                                echo "<td><i class='fa-solid fa-file'></td>";
                            } else {
                                echo "<td><i class='fa-solid fa-0'></i></td>";
                            }
                        ?>
                            <td id="hit"><?=$board_hit?></td>
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
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_board_free.php?page=$page";
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