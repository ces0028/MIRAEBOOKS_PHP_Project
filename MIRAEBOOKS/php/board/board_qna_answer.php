<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board_post.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/board_js.js"></script>
</head>
<body>
    <header>
        <?php
            // HEADER 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            // 관리자에 한하여 접속 허가
            if ($_SESSION['user_level'] != 999) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                exit();
            }

            // qna_id, page 값이 없을 경우에는 리턴
            if (isset($_GET['qna_id']) && isset($_GET['page'])) {
                $qna_id = $_GET['qna_id'];
                $page = $_GET['page'];
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_admin.php";

            // 데이터베이스 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
            // qna_id에 해당되는 문의사항을 출력
            $sql_select_qna = "SELECT * FROM qna_board_question WHERE qna_id = $qna_id";
            $record_set = mysqli_query($con, $sql_select_qna);
            $row = mysqli_fetch_array($record_set);
            $qna_id = $row['qna_id'];
            $qna_member_id = $row['qna_member_id'];
            $qna_question_title = $row['qna_question_title'];
            $qna_question_content = $row['qna_question_content'];
            $qna_question_register_date = $row['qna_question_register_date'];       
            $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$qna_member_id'";
            $record_get_name = mysqli_query($con, $sql_get_name);
            if (mysqli_num_rows($record_get_name) < 1) {
                $member_name = '알 수 없음';
            } else {
                $record_name = mysqli_fetch_array($record_get_name);
                $member_name = $record_name['member_name'];
            }
            
            // qna_id에 해당되는 답변을 출력
            $sql_select_answer = "SELECT * FROM qna_board_answer WHERE question_qna_id = '$qna_id'";
            $record_set_answer = mysqli_query($con, $sql_select_answer);
            $isexist_answer = mysqli_num_rows($record_set_answer);
            $row_answer = mysqli_fetch_array($record_set_answer);
            // 아직 답변이 달리지 않은 경우에는 답변을 입력할 수 있는 textarea 출력
            if ($row_answer == NULL) {
                $answer = "<td id='qna_answer'><textarea name='qna_answer' id='qna_answer'></textarea></td>";
            // 이미 답변이 달린 경우에는 답변을 출력(수정 불가)
            } else {
                $answer = "<td id='qna_answer'><textarea name='qna_answer' id='qna_answer' readonly>".$row_answer['qna_answer']."</textarea></td>";
            }
        ?>
        <section>
            <div id="main_header">
                <h2>ADMIN > BOARD > Q & A > ANSWER</h2>
            </div>
            <?php
                if (isset($_GET['error'])) {
            echo "<p class='error active'> {$_GET['error']} </p>";
                } else {
            ?>
                <p class="error"></p>
            <?php
                }
            ?>
            <form name="answer_write_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/board_server.php?mode=answer_insert&page=<?=$page?>" method="post">
                <table id="qna_board_answer">
                    <tr>
                        <th>작성자</th>
                        <td id="qna_member_id"><?=$member_name?>(<?=$qna_member_id?>)</td>
                        <input type="hidden" name="qna_id" value="<?=$qna_id?>">
                    </tr>
                    <tr>
                        <th>작성일자</th>
                        <td id="qna_question_register_date"><?=$qna_question_register_date?></td>
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td id="qna_question_title"><?=$qna_question_title?></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td>
                            <textarea id="qna_question_content" readonly><?=$qna_question_content?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>답변</th>
                        <?=$answer?>
                    </tr>
                </table>
                <div id="buttons">
                    <!-- 서버로 데이터를 넘기기 전에 먼저 미입력된 값이 없는지를 자바스크립트로 확인 -->
                    <input type="button" id="post_button" onclick="checkInsertQnaAnswer()" value="작성">
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/admin/admin_board_qna.php?page=<?=$page?>"><input type="button" id="list_button" value="목록"></a>
                </div>
            </form>
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