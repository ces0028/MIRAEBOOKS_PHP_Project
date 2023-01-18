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

            // GET방식을 통해 page와 view 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1과 list를 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $view = isset($_GET['view']) ? $_GET['view'] : 'list';

            // 검색을 한 경우에 한하여 search_scope와 search_keyword값을 입력
            $search_scope = $search_keyword = "";
            if (isset($_POST['search_scope']) && !empty($_POST['search_scope']) && isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])) {
                $search_scope = $_POST['search_scope'];
                $search_keyword = $_POST['search_keyword'];
            }
        ?>
    </header>
    <main>
        <?php
            // NAVIGATION BAR(MENU BAR) 연결
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_board.php";
        ?>
        <section>
            <div id="main_header">
                <h2>BOARD > FREE BOARD</h2>
                <div id="icon">
                    <!-- 아이콘을 클릭해서 게시물을 리스트형 또는 이미지형으로 볼 수 있도록 함 -->
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_free.php?view=list">
                        <i class="fa-solid fa-list"></i>
                    </a>
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_free.php?view=image">
                        <i class="fa-regular fa-image"></i>
                    </a>
                </div>
            </div>
            <form action='http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_free.php?view=<?=$view?>' name='board_free_search_form' method="POST" id="search_box">
                <select name='search_scope' id="search_scope">
                    <?php
                    // 검색한 결과가 페이지가 리로드 돼도 남아있게 하기 위함
                    if ($search_scope) {
                        if ($search_scope == 'board_title') {
                    ?>
                    <option value='board_title' selected>제목</option>
                    <option value='board_content'>내용</option>
                    <option value='board_member_id'>작성자</option>
                    <?php
                        } else if ($search_scope == 'board_content') {
                    ?>
                    <option value='board_title'>제목</option>
                    <option value='board_content' selected>내용</option>
                    <option value='board_member_id'>작성자</option>
                    <?php
                        } else {
                    ?>
                    <option value='board_title'>제목</option>
                    <option value='board_content'>내용</option>
                    <option value='board_member_id' selected>작성자</option>
                    <?php
                        }
                    } else {
                    ?>
                    <option value='board_title'>제목</option>
                    <option value='board_content'>내용</option>
                    <option value='board_member_id'>작성자</option>
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
            <?php
                // 리스트형으로 출력할 때
                if ($view == 'list') {
            ?>
            <table id="free_board">
                <tr>
                    <th id="number">번호</th>
                    <th id="board_title">제목</th>
                    <th id="board_member_id">작성자</th>
                    <th id="board_file">첨부</th>
                    <th id="board_register_date">등록일</th>
                    <th id="board_hit">조회수</th>
                </tr>
            <?php
                // 데이터베이스 연결
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                // 검색을 한 경우에 한하여 검색 결과를 조건으로 값을 출력
                // 검색을 하지 않은 경우에는 전체 값을 출력
                if ($search_scope && $search_keyword) {
                    $sql_get_size = "SELECT COUNT(*) FROM board WHERE (board_type = 'free') AND ($search_scope LIKE '%".$search_keyword."%')"; 
                } else {
                    $sql_get_size = "SELECT COUNT(*) FROM board WHERE board_type = 'free'";
                }
                $record_set_count = mysqli_query($con, $sql_get_size);
                $count_row = mysqli_fetch_array($record_set_count);
                $count = $count_row['COUNT(*)'];
                $scale = 10;
                $total_page = ceil($count / $scale);
                // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                $start = ($page - 1) * $scale;
                
                // 게시물의 총 갯수를 구할 때와 마찬가지로 검색어가 있을 경우에 한하여 조건을 걸어서 값을 출력
                if ($search_scope && $search_keyword) {
                    $sql_select_board = "SELECT * FROM board WHERE (board_type = 'free') AND ($search_scope LIKE '%".$search_keyword."%') ORDER BY board_id DESC LIMIT $start, $scale";
                } else {
                    $sql_select_board = "SELECT * FROM board WHERE board_type = 'free' ORDER BY board_id DESC LIMIT $start, $scale";
                }
                $record_set = mysqli_query($con, $sql_select_board);

                // 등록된 게시물이 없거나, 검색결과에 해당되는 값이 하나도 없을 경우에 출력
                if ($count == 0) {
                    if ($search_scope && $search_keyword) {
                        echo "<tr><td colspan='6'>검색 결과가 없습니다</td></tr>";
                    } else {
                        echo "<tr><td colspan='6'>등록된 게시물이 없습니다</td></tr>";
                    }
                } else {
                    $number = $count - $start;
                    // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                    // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                    while($row = mysqli_fetch_array($record_set)) {
                        $board_id = $row['board_id'];
                        $board_member_id = $row['board_member_id'];
                        $board_title = $row['board_title'];
                        $board_register_date = $row['board_register_date'];
                        $board_register_date = substr($board_register_date, 0, 10);
                        $board_hit = $row['board_hit'];
                        $board_file_name = $row['board_file_name'];
                        $board_file_type = $row['board_file_type'];
                        $board_file_path = $row['board_file_path'];
                        $board_file_saved_name = $row['board_file_saved_name'];
                        // 첨부파일이 있을 경우에는 파일을 다운받을 수 있는 링크가 연결된 아이콘을 생성함
                        if ($board_file_name) {
                            $file_image = "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/server/board_server.php?&mode=download&board_file_name=".$board_file_name."&board_file_type=".$board_file_type."&board_file_path=".$board_file_path."&board_file_saved_name=".$board_file_saved_name."'><i class='fa-solid fa-file'></i></a>";
                        } else {
                            $file_image = "";
                        }
                        // 함께 표시할 이름을 가져옴
                        $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$board_member_id'";
                        $record_get_name = mysqli_query($con, $sql_get_name);
                        if (mysqli_num_rows($record_get_name) < 1) {
                            $member_name = '알 수 없음';
                        } else {
                            $record_name = mysqli_fetch_array($record_get_name);
                            $member_name = $record_name['member_name'];
                        }

                        // 함께 표시할 게시물에 달린 댓글의 갯수를 가져옴
                        $select_reply_count = "SELECT COUNT(*) FROM board_reply WHERE reply_post = $board_id";
                        $record_get_reply_count = mysqli_query($con, $select_reply_count);
                        $record_count = mysqli_fetch_array($record_get_reply_count);
                        $reply_count = $record_count['COUNT(*)'];
                ?>
                <tr>
                    <td><?=$number?></td>
                    <td id="board_title">
                        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=free&board_id=<?=$board_id?>&page=<?=$page?>&view=list" id="board_title"><?=$board_title?><span id='reply_count'>[<?=$reply_count?>]</span></a>
                    </td>
                    <td id="board_member_id"><?=$member_name?>(<?=$board_member_id?>)</td>
                    <td id="board_file"><?=$file_image?></td>
                    <td id="board_register_date"><?=$board_register_date?></td>
                    <td id="board_hit"><?=$board_hit?></td>
                </tr>
                <?php
                        $number--;
                    }
                    mysqli_close($con);
                }
                ?>
            </table>
            <?php
                } else {
            ?>
                <!-- 게시판이 이미지형일 때 -->
                <div id="image_board">
            <?php   
                    // 데이터베이스 연결
                    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                    // 검색어가 있을 때와 없을 때 쿼리문을 달리함
                    if ($search_scope && $search_keyword) {
                        $sql_get_size = "SELECT COUNT(*) FROM board WHERE (board_type = 'free') AND ($search_scope LIKE '%".$search_keyword."%')"; 
                    } else {
                        $sql_get_size = "SELECT COUNT(*) FROM board WHERE board_type = 'free'";
                    }
                    $record_set_count = mysqli_query($con, $sql_get_size);
                    $count_row = mysqli_fetch_array($record_set_count);
                    $count = $count_row['COUNT(*)'];
                    $scale = 4;
                    $total_page = ceil($count / $scale);
                    $start = ($page - 1) * $scale;
                    
                    // 검색어가 있을 때와 없을 때 쿼리문을 달리함
                    if ($search_scope && $search_keyword) {
                        $sql_select_board = "SELECT * FROM board WHERE (board_type = 'free') AND ($search_scope LIKE '%".$search_keyword."%') ORDER BY board_id DESC LIMIT $start, $scale";
                    } else {
                        $sql_select_board = "SELECT * FROM board WHERE board_type = 'free' ORDER BY board_id DESC LIMIT $start, $scale";
                    }
                    $record_set = mysqli_query($con, $sql_select_board);
                    // 이미지 최대 크기를 지정함
                    $image_max_width = 200;
                    $image_max_height = 200;

                    while($row = mysqli_fetch_array($record_set)) {
                        $board_id = $row['board_id'];
                        $board_member_id = $row['board_member_id'];
                        $board_title = $row['board_title'];
                        $board_register_date = $row['board_register_date'];
                        $board_register_date = substr($board_register_date, 0, 10);
                        $board_hit = $row['board_hit'];
                        $board_file_name = $row['board_file_name'];
                        $board_file_type = $row['board_file_type'];
                        $board_file_path = $row['board_file_path'];
                        $board_file_saved_name = $row['board_file_saved_name'];
                        if (strpos($board_file_type,"image") !== false) {
                            $image_info = getimagesize($board_file_path.$board_file_saved_name);
                            $image_width = $image_info[0];
                            $image_height = $image_info[1];
                            // 상기에서 정한 이미지 최대크기와 가져올 이미지의 크기를 비교해서 더 작은 걸 선택
                            $image_width = ($image_width > $image_max_width) ? $image_max_width : $image_width;
                            $image_height = ($image_height > $image_max_width) ? $image_max_height : $image_height;
                            $file_image = "<img src='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/data/".$board_file_saved_name."' width='".$image_width."'px height='".$image_height."'px id='file_image'>";
                        } else {
                            // 첨부된 파일이 없거나, 첨부된 파일이 이미지 파일이 아닐 경우에는 지정된 디폴트 이미지를 대신 출력
                            $file_image = "<img src='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/source/img/default_image.png'  id='file_image'>";
                        }
                        // 함께 표시할 회원 이름을 가져옴
                        $sql_get_name = "SELECT member_name FROM members WHERE member_id = '$board_member_id'";
                        $record_get_name = mysqli_query($con, $sql_get_name);
                        $record_name = mysqli_fetch_array($record_get_name);
                        $member_name = $record_name['member_name'];
                    
            ?>
                <ul id="image_post">
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/board/board_view.php?type=free&board_id=<?=$board_id?>&page=<?=$page?>&view=image">
                        <li><?=$file_image?></li>
                        <li id="board_title"><?=$board_title?></li>
                        <li id="board_member_id"><?=$member_name?>(<?=$board_member_id?>)</li>
                        <li id="board_register_date"><?=$board_register_date?></li>
                    </a>
                </ul>
            <?php
                    }
                }
            ?>
            </div>
            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/board/board_post.php?type=free'>
                <input type="button" value="글쓰기" id="post_button">
            </a>
            <ul id="page_num">
            <?php
                // 함수를 이용하여 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/board/board_free.php?view=$view&page=$page";
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