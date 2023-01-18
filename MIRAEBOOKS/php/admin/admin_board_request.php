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
            $scale = 5;
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
                <h2>ADMIN > BOARD > REQUEST</h2>
            </div>
            <form name="board_manage_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=admin_delete" method="post">
                <table id="board_request_table">
                    <tr>
                        <th id="number">번호</th>
                        <th id="request_lang">언어</th>
                        <th id="request_title">제목</th>
                        <th id="request_author">작가</th>
                        <th id="request_member_id">신청자</th>
                        <th id="request_register_date">신청일자</th>
                        <th id="request_result">처리여부</th>
                    </tr>
                    <?php
                        // 데이터베이스 연결
                        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                        // 전체 게시물 갯수를 가져옴
                        // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                        // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                        $sql_get_size = "SELECT COUNT(*) FROM request_board";
                        $record_set_count = mysqli_query($con, $sql_get_size);
                        $count_row = mysqli_fetch_array($record_set_count);
                        $count = $count_row['COUNT(*)'];
                        // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                        $total_page = ceil($count / $scale);
                        
                        // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                        $sql_select_request = "SELECT * FROM request_board ORDER BY request_id DESC LIMIT $start, $scale";
                        $record_set = mysqli_query($con, $sql_select_request);
                        $total_record = mysqli_num_rows($record_set);

                        // 만약 등록된 신청 내역이 없다면 대신 등록된 내역이 없다는 문구를 출력
                        if ($total_record == 0) {
                            echo "
                                <tr>
                                    <td colspan='7'>등록된 신청 내역이 없습니다</td>
                                </tr>
                            ";
                        } else {
                            $number = $count - $start;
                            // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                            // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                            while($row = mysqli_fetch_array($record_set)) {
                                $request_id = $row['request_id'];
                                $request_member_id = $row['request_member_id'];
                                $request_lang = $row['request_lang'];
                                $request_title = $row['request_title'];
                                $request_author = $row['request_author'];
                                $request_register_date = $row['request_register_date'];
                                $request_result = $row['request_result'];
                                
                                // 함께 출력할 회원 이름을 가져옴
                                $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$request_member_id'";
                                $record_get_name = mysqli_query($con, $sql_get_name);
                                if (mysqli_num_rows($record_get_name) < 1) {
                                    $member_name = '알 수 없음';
                                } else {
                                    $record_name = mysqli_fetch_array($record_get_name);
                                    $member_name = $record_name['member_name'];
                                }
                        ?>
                        <tr>
                            <td rowspan='4' id='board_line'><?=$number?></td>
                            <td rowspan='4' id='board_line'><?=$request_lang?></td>
                            <td rowspan='4' id='board_line'><?=$request_title?></td>
                            <td rowspan='4' id='board_line'><?=$request_author?></td>
                            <td rowspan='4' id='board_line'><?=$member_name?>(<?=$request_member_id?>)</td>
                            <td rowspan='4' id='board_line'><?=$request_register_date?></td>
                            <td>
                                <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/board_server.php?mode=update_request_result&request_id=<?=$request_id?>&result=0&page=<?=$page?>'>
                                <?php
                                    // request_result에 따라서 값을 달리 출력함
                                    if ($request_result == 0) {
                                        echo "<button type='button' id='undetermined'>대기</button>";
                                    } else {
                                        echo "<button type='button' id='none'>대기</button>";
                                    }
                                ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/board_server.php?mode=update_request_result&request_id=<?=$request_id?>&result=1&page=<?=$page?>'>
                                <?php
                                    if ($request_result == 1) {
                                        echo "<button type='button' id='defer'>보류</button>";
                                    } else {
                                        echo "<button type='button' id='none'>보류</button>";
                                    }
                                ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/board_server.php?mode=update_request_result&request_id=<?=$request_id?>&result=2&page=<?=$page?>'>
                                <?php
                                    if ($request_result == 2) {
                                        echo "<button type='button' id='veto'>불승인</button>";
                                    } else {
                                        echo "<button type='button' id='none'>불승인</button>";
                                    }
                                ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td id='board_line'>
                                <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/board_server.php?mode=update_request_result&request_id=<?=$request_id?>&result=3&page=<?=$page?>'>
                                <?php
                                    if ($request_result == 3) {
                                        echo "<button type='button' id='uploaded'>완료</button>";
                                    } else {
                                        echo "<button type='button' id='none'>완료</button>";
                                    }
                                ?>
                                </a>
                            </td>
                        </tr>
                        <?php
                                $number--;
                            }
                            mysqli_close($con);
                        }
                        ?>
                    </table>
                </form>
            <ul id="page_num">
            <?php
                // 함수를 사용하여 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_board_request.php?page=$page";
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