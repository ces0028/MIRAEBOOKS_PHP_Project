<?php
    // 로컬서버인 관계로 공개해도 무방함
    $con = mysqli_connect("localhost","root","123456");
    if (!$con) {
        die('FAILURE TO CONNECT DATABASE'.mysqli_connect_errno());
    }

    // 테이블을 만들기에 앞서서 데이터베이스가 만들어졌는지 먼저 확인하는 작업
    $database_flag = false;
    $sql_show = "SHOW DATABASES";
    $result = mysqli_query($con, $sql_show) or die('THERE IS NO DATABASE'.mysqli_errno());
    while($row = mysqli_fetch_assoc($result)) {
        if ($row['Database'] == 'miraebooksdb') {
            $database_flag = true;
            break;
        }
    }

    // 데이터베이스가 없다면 데이터베이스를 새로 생성
    if ($database_flag == false) {
        $sql_create = "CREATE DATABASE miraebooksdb";
        $result = mysqli_query($con, $sql_create) or die('FAILURE TO CREATE DATABASE'.mysqli_errno());
        if ($result) {
            echo "<script>alert('SUCCESS TO CREATE DATABASE')</script>";
        }
    }
    
    // 데이터베이스를 선택
    $dbcon = mysqli_select_db($con, 'miraebooksdb') or die('FAILURE TO SELECT DATABASE'. mysqli_error());
    if ($dbcon == false) {
        echo "<script>alert('FAILURE TO SELECT DATABASE')</script>";
    }

    // 지정된 갯수에 맞춰서 페이지를 출력하는 함수
    function get_paging($show_pages, $current_page, $total_page, $url) {
        // 지정된 페이지 값을 초기화
        $url = preg_replace('/page=[0-9]/', '', $url) . 'page=';
        // 페이지 코드를 입력할 변수를 생성
        $str = '';

        // 현재 페이지가 1보다 클 때 앞 페이지로 이동하는 버튼을 생성
        if ($current_page > 1) {
            $str = '<a href ="'. $url .'1"> << </a>' . PHP_EOL;
        } else {
            $str = '';
        }
        
        // 시작 페이지와 마지막 페이지를 지정
        $start_page = (((int)(($current_page - 1) / $show_pages)) * $show_pages) + 1;
        $end_page = $start_page + $show_pages - 1;

        // 마지막 페이지가 총 페이지 수보다 클 때는 마지막 페이지를 총 페이지 수로 지정
        if ($end_page >= $total_page) {
            $end_page = $total_page;
        }

        // 시작 페이지가 1보다 클 때 앞 페이지로 이동하는 버튼을 생성
        if ($start_page > 1) {
            $str .= '<a href="' . $url . ($start_page - 1) . '" class="arrow prev"> <- </a>' . PHP_EOL;
        }

        // 전체 페이지가 0보다 클 때 = 게시판에 게시물이 하나라도 등록돼있디면, 페이지를 생성
        if ($total_page > 0) {
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($current_page != $i) {
                    $str .= '<a href="'.$url.$i.'" class="">'.$i.'</a>' .PHP_EOL;
                } else {
                    $str .= '<a href="'.$url.$i.'" class="active">'.$i.'</a>' .PHP_EOL;
                }
            }
        }

        // 전체 페이지가 출력되는 마지막 페이지보다 클 경우에는 그 다음 페이지로 이동하는 버튼을 생성
        if ($total_page > $end_page) {
            $str .= '<a href="' . $url . ($end_page + 1) . '" class="arrow next"> -> </a>' . PHP_EOL;
        }

        // 현재 페이지가 총 페이지 보다 작다면 마지막 페이지로 이동하는 버튼을 생성
        if ($current_page < $total_page) {
            $str .= '<a href ="'. $url .$total_page.'"> >> </a>' . PHP_EOL;
        }

        // 페이지가 생성될 조건을 하나라도 갖췄다면
        if ($str) {
            // 상기에서 입력된 값을 출력
            echo "<li class='pg_wrap'><span class='pg'>{$str}</span></li>";
        } else {
            // 상기의 조건에 하나도 해당되지 않았다면 그냥 빈 값으로 출력
            echo "";
        }
    }
?>