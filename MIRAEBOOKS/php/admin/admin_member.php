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
            
            // GET방식을 통해 page와 sort 값을 받는데, 만약 없을 경우에는 초기값으로 각각 1과 6을 줌
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 6;

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
                    $sort = 'member_id'; 
                    break;
                case 2: 
                    $sort = 'member_name'; 
                    break;
                case 3: 
                    $sort = 'member_gender'; 
                    break;
                case 4: 
                    $sort = 'member_email'; 
                    break;
                case 5: 
                    $sort = 'member_tel'; 
                    break;
                case 6: 
                    $sort = 'member_register_date'; 
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
                <h2>ADMIN > MEMBER</h2>
            </div>
            <form name="member_management_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/member_server.php?mode=admin_withdrawal" method="post">
            <table id="member_table">
                <tr>
                    <!-- 동시에 여러 개의 값을 선택하기 위해서 checkbox 생성 -->
                    <th id="select">
                        <input type="checkbox" id="select_button" onclick="checkALL()">
                    </th>
                    <th id="number">번호</th>
                    <!-- 제목줄을 클릭하면 값이 정렬이 되도록 앵커 생성 -->
                    <th id="member_id">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=1&direction=<?=$direction?>'>아이디</a>
                    </th>
                    <th id="member_name">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=2&direction=<?=$direction?>'>이름</a>
                    </th>
                    <th id="member_gender">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=3&direction=<?=$direction?>'>성별</a>
                    </th>
                    <th id="member_email">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=4&direction=<?=$direction?>'>이메일</a>
                    </th>
                    <th id="member_tel">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=5&direction=<?=$direction?>'>전화번호</a>
                    </th>
                    <th id="member_register_date">
                        <a href='http://<?=$_SERVER['HTTP_HOST']?>/MIRAEBOOKS/php/admin/admin_member.php?sort=6&direction=<?=$direction?>'>가입일</a>
                    </th>
                </tr>
                <?php
                    // 데이터베이스 연결
                    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
                    // 전체 게시물 갯수를 가져옴
                    // 전체 게시물을 가져오지 않고 갯수만 구하는 이유?
                    // 페이지를 나눠서 한 페이지당 10개의 게시물만 필요한데, 모든 게시물이라는 과도한 데이터를 가져오는 건 낭비이기 때문!
                    $sql_get_size = "SELECT COUNT(*) FROM members WHERE member_level != 999";
                    $record_set_count = mysqli_query($con, $sql_get_size);
                    $count_row = mysqli_fetch_array($record_set_count);
                    $count = $count_row['COUNT(*)'];
                    // 총 게시물 수를 위에서 정한 scale로 나눠 소숫점을 올림으로써 총 필요한 페이지 수를 정함
                    $total_page = ceil($count / $scale);
                    
                    // 페이지에 출력한 만큼의 데이터 만을 가져와서 화면에 출력함
                    $sql_select_member = "SELECT * FROM members WHERE member_level != 999 ORDER BY ".$sort." ".$direction." LIMIT $start, $scale";
                    $record_set = mysqli_query($con, $sql_select_member);
                    $total_record = mysqli_num_rows($record_set);
                    
                    // 만약 등록된 게시물이 없다면 대신 등록된 게시물이 없다는 문구를 출력
                    if ($total_record == 0) {
                        echo "
                            <tr>
                                <td colspan='9'>등록된 회원이 없습니다</td>
                            </tr>
                        ";
                    } else {
                        $number = $count - $start;
                        // 반복문으로 record_set에 담긴 값을 하나 씩 가져와서 출력한다.
                        // mysqli_fetch_array($record_set) => 더 이상 가져올 값이 없으면 false를 출력하기 때문에 반복문을 종료함
                        while($row = mysqli_fetch_array($record_set)) {
                            $member_id = $row['member_id'];
                            $member_name = $row['member_name'];
                            $member_gender = $row['member_gender'];
                            $member_email = $row['member_email'];
                            $member_tel = $row['member_tel'];
                            $member_register_date = $row['member_register_date'];
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="checkbox[]" id="checkbox" value="<?=$member_id?>">
                        </td>
                        <td><?=$number?></td>
                        <td><?=$member_id?></td>
                        <td><?=$member_name?></td>
                        <td><?=$member_gender?></td>
                        <td><?=$member_email?></td>
                        <td><?=$member_tel?></td>
                        <td><?=$member_register_date?></td>
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
                $url = "http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/admin/admin_member.php?page=$page";
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