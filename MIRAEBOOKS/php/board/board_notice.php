<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/board.css">
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
            
            // 검색어가 있을 때에 한하여 쿼리문을 달리 사용하기 위해서 값을 입력
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
                <h2>BOARD > NOTICE BOARD</h2>
            </div>
            <form action='http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_notice.php' name='board_free_search_form' method="POST" id="search_box">
                <select name='search_scope' id="search_scope">
                <?php
                    // 검색한 결과가 페이지가 리로드 돼도 남아있게 하기 위함
                    if ($search_scope) {
                        if ($search_scope == 'board_title') {
                    ?>
                    <option value='board_title' selected>제목</option>
                    <option value='board_content'>내용</option>
                    <?php
                        } else {
                    ?>
                    <option value='board_title'>제목</option>
                    <option value='board_content' selected>내용</option>
                    <?php
                        }
                    } else { 
                    ?>  
                    <option value='board_title'>제목</option>
                    <option value='board_content'>내용</option>
                    <?php
                    }
                    ?>
                </select>
                <?php
                    if ($search_keyword) {
                        echo "<input type='text' name='search_keyword' id='search_keyword' value=".$search_keyword." maxlength='20'>";
                    } else {
                        echo "<input type='text' name='search_keyword' id='search_keyword' maxlength='20'>";
                    }
                ?>
                <input type='submit' value='검색' id='search_button'>
            </form>
            <table id="notice_board">
                <tr>
                    <th id="number">번호</th>
                    <th id="board_title">제목</th>
                    <th id="board_member_id">작성자</th>
                    <th id="board_register_date">등록일</th>
                    <th id="board_hit">조회수</th>
                </tr>
            <?php
                // 데이터베이스 연결
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                // 검색어가 있을 경우에 한하여 쿼리문을 달리함
                if ($search_scope && $search_keyword) {
                    $sql_get_size = "SELECT COUNT(*) FROM board WHERE (board_type = 'notice') AND ($search_scope LIKE '%".$search_keyword."%')";
                } else {
                    $sql_get_size = "SELECT COUNT(*) FROM board WHERE board_type = 'notice'";
                }
                $record_set_count = mysqli_query($con, $sql_get_size);
                $count_row = mysqli_fetch_array($record_set_count);
                $count = $count_row['COUNT(*)'];
                $total_page = ceil($count / $scale);
                
                if ($search_scope && $search_keyword) {
                    $sql_select_board = "SELECT * FROM board WHERE (board_type = 'notice') AND ($search_scope LIKE '%".$search_keyword."%') ORDER BY board_id DESC LIMIT $start, $scale";
                    $sql_get_size = "SELECT COUNT(*) FROM board WHERE (board_type = 'notice') AND ($search_scope LIKE '%".$search_keyword."%') ";
                } else {
                    $sql_select_board = "SELECT * FROM board WHERE board_type = 'notice' ORDER BY board_id DESC LIMIT $start, $scale";
                }
                $record_set = mysqli_query($con, $sql_select_board);

                // 등록된 게시물이 없거나 검색결과가 없을 경우 아래와 같이 출력함
                if ($count == 0) {
                    if ($search_scope && $search_keyword) {
                        echo "<tr><td colspan='6'>검색 결과가 없습니다</td></tr>";
                    } else {
                        echo "<tr><td colspan='6'>등록된 공지사항이 없습니다</td></tr>";
                    }
                } else {
                    $number = $count - $start;
                    while($row = mysqli_fetch_array($record_set)) {
                        $board_id = $row['board_id'];
                        $board_member_id = $row['board_member_id'];
                        $board_title = $row['board_title'];
                        $board_register_date = $row['board_register_date'];
                        $board_register_date = substr($board_register_date, 0, 10);
                        $board_hit = $row['board_hit'];

                        $select_reply_count = "SELECT COUNT(*) FROM board_reply WHERE reply_post = $board_id";
                        $record_get_reply_count = mysqli_query($con, $select_reply_count);
                        $record_count = mysqli_fetch_array($record_get_reply_count);
                        $reply_count = $record_count['COUNT(*)'];
                ?>
                <tr>
                    <td><?=$number?></td>
                    <td>
                        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=notice&board_id=<?=$board_id?>&page=<?=$page?>"><?=$board_title?><span id='reply_count'>[<?=$reply_count?>]</span></a>
                    </td>
                    <td>관리자</td>
                    <td><?=$board_register_date?></td>
                    <td><?=$board_hit?></td>
                </tr>
                <?php
                        $number--;
                    }
                    mysqli_close($con);
                }
                ?>
            </table>
            <?php
                // 관리자에 한하여 글쓰기 기능을 활성화
                if ($user_level == 999) {
            ?>
            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/board/board_post.php?type=notice'>
                <input type="button" value="글쓰기" id="post_button">
            </a>
            <?php
                }
            ?>
            </div>
            <ul id="page_num">
            <?php
                // 함수를 사용해서 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_notice.php?page=$page";
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