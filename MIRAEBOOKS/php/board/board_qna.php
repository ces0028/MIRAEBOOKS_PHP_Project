<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/board_js.js"></script>
</head>
<body onload="callJs()">
    <header>
        <?php
            // HEADER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            // 회원에 한하여 접속 허가
            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }
            // GET방식을 통해 page 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            // 한 페이지에 몇 개의 게시물이 출력될 건지를 지정
            $scale = 8;
            // 쿼리문에서 몇 번째 값부터 가져올지를 지정
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
                <h2>BOARD > Q & A BOARD</h2>
            </div>
            <table id="qna_board">
                <tr id="qna_board_head">
                    <th id="number">번호</th>
                    <th id="qna_question_title">제목</th>
                    <th id="qna_member_id">작성자</th>
                    <th id="qna_question_register_date">작성일자</th>
                </tr>
            <?php
                // 데이터베이스 연결
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                $sql_get_size = "SELECT COUNT(*) FROM qna_board_question";
                $record_set_count = mysqli_query($con, $sql_get_size);
                $count_row = mysqli_fetch_array($record_set_count);
                $count = $count_row['COUNT(*)'];
                $total_page = ceil($count / $scale);
                
                $sql_select_question = "SELECT * FROM qna_board_question ORDER BY qna_id DESC LIMIT $start, $scale";
                $record_set = mysqli_query($con, $sql_select_question);
                $total_record = mysqli_num_rows($record_set);
                if ($total_record == 0) {
                    echo "
                        <tr>
                            <td colspan='5'>등록된 게시물이 없습니다</td>
                        </tr>
                    ";
                } else {
                    $number = $count - $start;
                    while($row = mysqli_fetch_array($record_set)) {
                        $qna_id = $row['qna_id'];
                        $qna_member_id = $row['qna_member_id'];
                        $qna_question_title = $row['qna_question_title'];
                        $qna_question_content = $row['qna_question_content'];
                        $qna_question_register_date = $row['qna_question_register_date'];
                        $qna_question_register_date = substr($qna_question_register_date, 0, 10);
                        $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$qna_member_id'";
                        $record_get_name = mysqli_query($con, $sql_get_name);
                        if (mysqli_num_rows($record_get_name) < 1) {
                            $member_name = '알 수 없음';
                        } else {
                            $record_name = mysqli_fetch_array($record_get_name);
                            $member_name = $record_name['member_name'];
                        }
                    ?>
                <tr id="question_title">
                    <td><?=$number?></td>
                    <td class="question_title"><?=$qna_question_title?></td>
                    <td><?=$member_name?>(<?=$qna_member_id?>)</td>
                    <td><?=$qna_question_register_date?></td>
                </tr>
                <?php
                    // 답변을 입력한 회원이나 관리자에 한하여 답변을 열람 가능
                    if ($user_id == $qna_member_id || $user_level == 999) {
                ?>
                    <tr class="question_content">
                        <th>Q</th>
                        <td colspan='3'><?=$qna_question_content?></td>
                    </tr>
                <?php
                    // 아닐 경우에는 답변을 열람하지 못하도록 함
                    } else {
                ?>
                    <tr class="question_content">
                        <th>Q</th>
                        <td colspan='3'>작성자만 확인 할 수 있습니다</td>
                    </tr>
                <?php
                    } 
                ?>
                <?php
                    $sql_select_answer = "SELECT * FROM qna_board_answer WHERE question_qna_id = $qna_id";
                    $record_set_answer = mysqli_query($con, $sql_select_answer);
                    $isexist_answer = mysqli_num_rows($record_set_answer);
                    $row_answer = mysqli_fetch_array($record_set_answer);
                    if ($user_id != $qna_member_id && $user_level != 999) {
                        echo "
                            <tr class='answer'>
                                <th>A</th>
                                <td colspan='3'>작성자만 확인 할 수 있습니다</td>
                            </tr>
                        ";
                    // 답변이 아직 달리지 않은 경우에는 아직 등록되지 않았다는 문구 출력
                    } else if ($isexist_answer < 1) {
                        echo "
                            <tr class='answer'>
                                <th>A</th>
                                <td colspan='3'>아직 답변이 등록되지 않았습니다.</td>
                            </tr>
                        ";
                    // 작성자 또는 관리자에 한하여 답변 열람 가능
                    } else if ($user_id == $qna_member_id || $user_level == 999) {
                        $qna_answer = $row_answer['qna_answer'];
                        $qna_answer_register_date = $row_answer['qna_answer_register_date'];
                        echo "
                            <tr class='answer'>
                                <th>A</th>
                                <td colspan='3'>".$qna_answer."</td>
                            </tr>
                        ";
                    }
                ?>
                <?php
                        $number--;
                    }
                    mysqli_close($con);
                }
                ?>
            </table>
            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/board/board_post.php?type=qna'>
                <input type="button" value="글쓰기" id="post_button">
            </a>
            <ul id="page_num">
            <?php
                // 함수를 사용해서 페이지 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_qna.php?page=$page";
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