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

            // GET방식을 통해 page와 sort 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1과 0을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 0;

            // 클릭을 할 때마다 오름차순, 내림차순이 반복돼서 진행될 수 있게 함
            if (isset($_GET['direction'])) {
                $direction = $_GET['direction'];
                $direction = ($direction == 'DESC') ? 'ASC' : 'DESC';
            } else {
                $direction = 'ASC';
            }
            
            // 입력된 sort값에 따라서 쿼리문에 대입될 값을 부여함
            switch($sort) {
                case 0: 
                    $sort = 'ebook_id'; 
                    break;
                case 1: 
                    $sort = 'ebook_lang'; 
                    break;
                case 2: 
                    $sort = 'ebook_title'; 
                    break;
                case 3: 
                    $sort = 'ebook_author'; 
                    break;
                case 4: 
                    $sort = 'ebook_hit'; 
                    break;
                case 5: 
                    $sort = 'ebook_open'; 
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
                <h2>ADMIN > BOOKS</h2>
            </div>
            <form name="board_manage_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=multiple_update_open&page=<?=$page?>" method="post" enctype="multipart/form-data">
                <table id="book_table">
                    <tr>
                        <!-- 동시에 여러 개의 값을 선택하기 위해서 checkbox 생성 -->
                        <th id="select"><input type="checkbox" id="select_button" onclick="checkALL()"></th>
                        <!-- 제목줄을 클릭하면 값이 정렬이 되도록 앵커 생성 -->
                        <th id="ebook_id">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=0&direction=<?=$direction?>'>번호</a>
                        </th>
                        <th id="ebook_lang">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=1&direction=<?=$direction?>'>언어</a>
                        </th>
                        <th id="ebook_title">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=2&direction=<?=$direction?>'>제목</a>
                        </th>
                        <th id="ebook_author">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=3&direction=<?=$direction?>'>저자</a>
                        </th>
                        <th id="ebook_hit">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=4&direction=<?=$direction?>'>조회수</a>
                        </th>
                        <th id="ebook_file">파일</th>
                        <th id="ebook_open">
                            <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_books.php?sort=5&direction=<?=$direction?>'>공개여부</a>
                        </th>
                    </tr>
                    <?php
                        // 데이터베이스 연결
                        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                        // 전체 게시물 갯수를 가져옴
                        // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                        // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                        $sql_get_size = "SELECT COUNT(*) FROM ebook";
                        $record_set_count = mysqli_query($con, $sql_get_size);
                        $count_row = mysqli_fetch_array($record_set_count);
                        $count = $count_row['COUNT(*)'];
                        // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                        $total_page = ceil($count / $scale);
                        
                        // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                        $sql_select_books = "SELECT * FROM ebook ORDER BY ".$sort." ".$direction." LIMIT $start, $scale";
                        $record_set = mysqli_query($con, $sql_select_books);
                        $total_record = mysqli_num_rows($record_set);
                        
                        // 만약 등록된 E-BOOK이 없다면 대신 등록된 E-BOOK 없다는 문구를 출력
                        if ($total_record == 0) {
                            echo "
                                <tr>
                                    <td colspan='7'>등록된 E-BOOK이 없습니다</td>
                                </tr>
                            ";
                        } else {
                            $number = $count - $start;
                            // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                            // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                            while($row = mysqli_fetch_array($record_set)) {
                                $ebook_id = $row['ebook_id'];
                                $ebook_lang = $row['ebook_lang'];
                                $ebook_title = $row['ebook_title'];
                                $ebook_author = $row['ebook_author'];
                                $ebook_hit = $row['ebook_hit'];
                                $ebook_open = $row['ebook_open'];
                        ?>
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" id="checkbox" value="<?=$ebook_id?>"></td>
                            <td><?=$ebook_id?></td>
                            <td><?=$ebook_lang?></td>
                            <!-- 제목을 누르면 해당 HTML로 연결 -->
                            <td>
                                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_view&ebook_id=<?=$ebook_id?>">
                                    <?=$ebook_title?>
                                </a>
                            </td>
                            <td><?=$ebook_author?></td>
                            <td id="hit"><?=$ebook_hit?></td>
                            <!-- 파일 아이콘을 누르면 해당 PDF를 다운로드 -->
                            <td>
                                <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_download&ebook_id=<?=$ebook_id?>">
                                    <i class='fa-solid fa-file'></i>
                                </a>
                            </td>
                            <!-- 버튼을 눌러서 공개/미공개 여부를 변경 -->
                            <td>
                                <a href="http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_update_open&ebook_id=<?=$ebook_id?>&page=<?=$page?>">
                                <!-- ebook_open에 따라서 공개 버튼, 비공개 버튼을 출력 -->
                            <?php
                                if ($ebook_open == 1) {
                                    echo "<button type='button' id='public'>공개</button>";
                                } else {
                                    echo "<button type='button' id='private'>비공개</button>";
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
                    <button type="submit" id="update_button">변경</button>
                    <a href="http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/books/books_add.php">
                        <button type="button" id="add_button">추가</button>
                    </a>
                </form>
            <ul id="page_num">
            <?php
                // 함수를 사용하여 페이지를 출력
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_books.php?page=$page";
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