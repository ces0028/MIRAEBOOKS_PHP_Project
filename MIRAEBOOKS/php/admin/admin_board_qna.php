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
                <h2>ADMIN > BOARD > Q & A</h2>
            </div>
            <form name="board_manage_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=admin_delete" method="post" enctype="multipart/form-data">
                <table id="qna_board_table">
                    <tr>
                        <th id="number">번호</th>
                        <th id="qna_question_title">제목</th>
                        <th id="qna_member_id">작성자</th>
                        <th id="qna_question_register_date">작성일</th>
                        <th id="isexist_answer">답변여부</a></th>
                    </tr>
                    <?php
                        // 데이터베이스 연결
                        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                        // 전체 게시물 갯수를 가져옴
                        // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                        // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                        $sql_get_size = "SELECT COUNT(*) FROM qna_board_question";
                        $record_set_count = mysqli_query($con, $sql_get_size);
                        $count_row = mysqli_fetch_array($record_set_count);
                        $count = $count_row['COUNT(*)'];
                        // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                        $total_page = ceil($count / $scale);
                        
                        // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                        $sql_select_qna = "SELECT * FROM qna_board_question ORDER BY qna_id DESC LIMIT $start, $scale";
                        $record_set = mysqli_query($con, $sql_select_qna);
                        $total_record = mysqli_num_rows($record_set);
                        
                        // 만약 등록된 문의사항이 없다면 대신 등록된 문의사항이 없다는 문구를 출력
                        if ($total_record == 0) {
                            echo "
                                <tr>
                                    <td colspan='5'>등록된 문의사항이 없습니다</td>
                                </tr>
                            ";
                        } else {
                            $number = $count - $start;
                            // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                            // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                            while($row = mysqli_fetch_array($record_set)) {
                                $qna_id = $row['qna_id'];
                                $qna_member_id = $row['qna_member_id'];
                                $qna_question_title = $row['qna_question_title'];
                                $qna_question_register_date = $row['qna_question_register_date'];

                                $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$qna_member_id'";
                                $record_get_name = mysqli_query($con, $sql_get_name);
                                if (mysqli_num_rows($record_get_name) < 1) {
                                    $member_name = '알 수 없음';
                                } else {
                                    $record_name = mysqli_fetch_array($record_get_name);
                                    $member_name = $record_name['member_name'];
                                }
                        ?>
                        <tr>
                            <td><?=$number?></td>
                            <td>
                                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_qna_answer.php?qna_id=<?=$qna_id?>&page=<?=$page?>"><?=$qna_question_title?></a>
                            </td>
                            <td><?=$member_name?>(<?=$qna_member_id?>)</td>
                            <td><?=$qna_question_register_date?></td>
                            <?php
                                $sql_select_answer = "SELECT * FROM qna_board_answer WHERE question_qna_id = '$qna_id'";
                                $record_set_answer = mysqli_query($con, $sql_select_answer);
                                $isexist_answer = mysqli_num_rows($record_set_answer);
                                $row_answer = mysqli_fetch_array($record_set_answer);
                                // 등록된 답변이 없을 경우에는 답변을 달 수 있는 버튼 생성
                                if ($row_answer == NULL) {
                                    echo "
                                        <td>
                                            <a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_qna_answer.php?qna_id=".$qna_id."&page=".$page."'>
                                                <button type='button'>답변</button>
                                            </a>
                                        </td>
                                    ";
                                // 답변이 등록돼있을 경우에는 답변완료를 출력
                                } else {
                                    echo "<td>답변완료</td>";
                                }
                            ?>
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
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_board_qna.php?page=$page";
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