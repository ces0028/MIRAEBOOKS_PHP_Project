<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/board_js.js"></script>
</head>
<body>
    <header>
        <?php
            // HEADER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            // 회원에 한하여 접속 허가
            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }

            $search_scope = $search_keyword = "";
            if (isset($_POST['search_scope']) && !empty($_POST['search_scope']) && isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])) {
                $search_scope = $_POST['search_scope'];
                $search_keyword = $_POST['search_keyword'];
            }

            // GET방식을 통해 page 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $scale = 5;
            $start = ($page - 1) * $scale;
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_board.php";
        ?>
        <section>
            <div id="main_header">
                <h2>BOARD > REQUEST BOARD</h2>
            </div>
            <form action='http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_request.php?' name='board_free_search_form' method="POST" id="search_box">
                <select name='search_scope' id="search_scope">
                    <?php
                    // 검색한 결과가 페이지가 리로드 돼도 남아있게 하기 위함
                    if ($search_scope) {
                        if ($search_scope == 'request_lang') {
                    ?>
                    <option value='request_lang' selected>언어</option>
                    <option value='request_title'>제목</option>
                    <option value='request_author'>저자</option>
                    <option value='request_member_id'>신청자</option>
                    <?php
                        } else if ($search_scope == 'request_title') {
                    ?>
                    <option value='request_lang'>언어</option>
                    <option value='request_title' selected>제목</option>
                    <option value='request_author'>저자</option>
                    <option value='request_member_id'>신청자</option>
                    <?php
                        } else if ($search_scope == 'request_author') {
                    ?>
                    <option value='request_lang'>언어</option>
                    <option value='request_title'>제목</option>
                    <option value='request_author' selected>저자</option>
                    <option value='request_member_id'>신청자</option>
                    <?php
                        } else {
                    ?>
                    <option value='request_lang'>언어</option>
                    <option value='request_title'>제목</option>
                    <option value='request_author'>저자</option>
                    <option value='request_member_id' selected>신청자</option>
                    <?php
                        }
                    } else {
                    ?>
                    <option value='request_lang'>언어</option>
                    <option value='request_title'>제목</option>
                    <option value='request_author'>저자</option>
                    <?php
                    }
                    ?>
                </select>
                <?php
                    if ($search_keyword) {
                        echo "<input type='text' name='search_keyword' id='search_keyword' value=".$search_keyword.">";
                    } else {
                        echo "<input type='text' name='search_keyword' id='search_keyword'>";
                    }
                ?>
                <input type='submit' value='검색' id='search_button'>
            </form>
            <table id="request_board">
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
                // 검색어가 있을 경우에 한하여 쿼리문을 달리함
                if ($search_scope && $search_keyword) {
                    $sql_get_size = "SELECT COUNT(*) FROM request_board WHERE $search_scope LIKE '%".$search_keyword."%'";
                } else {
                    $sql_get_size = "SELECT COUNT(*) FROM request_board";
                }
                $record_set_count = mysqli_query($con, $sql_get_size);
                $count_row = mysqli_fetch_array($record_set_count);
                $count = $count_row['COUNT(*)'];
                $total_page = ceil($count / $scale);
                if ($search_scope && $search_keyword) {
                    $sql_select_board = "SELECT * FROM request_board WHERE $search_scope LIKE '%".$search_keyword."%' ORDER BY request_id DESC LIMIT $start, $scale";
                } else {
                    $sql_select_board = "SELECT * FROM request_board ORDER BY request_id DESC LIMIT $start, $scale";
                }
                $record_set = mysqli_query($con, $sql_select_board);
                $total_record = mysqli_num_rows($record_set);
                
                // 등록된 신청 내역이 없거나 검색결과가 없을 경우 아래와 같이 출력함
                if ($total_record == 0) {
                    if ($search_scope && $search_keyword) {
                        echo "<tr><td colspan='6'>검색 결과가 없습니다</td></tr>";
                    } else {
                        echo "<tr><td colspan='6'>등록된 신청 내역이 없습니다</td></tr>";
                    }
                } else {
                    $number = $count - $start;
                    while($row = mysqli_fetch_array($record_set)) {
                        $request_id = $row['request_id'];
                        $request_member_id = $row['request_member_id'];
                        $request_lang = $row['request_lang'];
                        $request_title = $row['request_title'];
                        $request_author = $row['request_author'];
                        $request_register_date = $row['request_register_date'];
                        $request_register_date = substr($request_register_date, 0, 10);
                        $request_result = $row['request_result'];
                        
                        $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$request_member_id'";
                        $record_get_name = mysqli_query($con, $sql_get_name);
                        if (mysqli_num_rows($record_get_name) < 1) {
                            $member_name = '알 수 없음';
                        } else {
                            $record_name = mysqli_fetch_array($record_get_name);
                            $member_name = $record_name['member_name'];
                        }
                        
                        // request_result에 따라서 버튼을 달리함
                        switch($request_result) {
                            case 0 :
                                $request_result_view = "<button id='undetermined'>대기</button>";
                                break;
                            case 1 :
                                $request_result_view = "<button id='defer'>보류</button>";
                                break;
                            case 2 : 
                                $request_result_view = "<button id='veto'>불승인</button>";
                                break;
                            case 3 : 
                                $request_result_view = "<button id='uploaded'>완료</button>";
                                break;
                            default :
                                $request_result_view = "<p> </p>";
                        }
                ?>
                <tr>
                    <td><?=$number?></td>
                    <td><?=$request_lang?></td>
                    <td><?=$request_title?></td>
                    <td><?=$request_author?></td>
                    <td><?=$member_name?>(<?=$request_member_id?>)</td>
                    <td><?=$request_register_date?></td>
                    <td><?=$request_result_view?></td>
                </tr>
                <?php
                        $number--;
                    }
                    mysqli_close($con);
                }
                ?>
            </table>
            </div>
            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/board/board_application.php'>
                <button id="request_button">신청</button>
            </a>
            <ul id="page_num">
            <?php
                // 함수를 사용해서 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_request.php?page=$page";
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