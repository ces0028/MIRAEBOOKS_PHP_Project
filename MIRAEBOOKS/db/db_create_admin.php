<?php
    // 관리자를 생성하는 함수
    // 로그인도 가능하게 해야하기 때문에 password_hash도 사용함
    function create_admin ($con, $admin_id) {
        $admin_flag = false;
        $sql_check_admin = "SELECT * FROM members WHERE member_id = '$admin_id'";
        $result = mysqli_query($con, $sql_check_admin) or die('프로시저 조회 실패'.mysqli_error($con));

        if (mysqli_num_rows($result) == 1) {
            $admin_flag = true;
        } else {
            $admin_password = password_hash('123456', PASSWORD_DEFAULT);
            $admin_name = '관리자';
            $admin_gender = 'F';
            $admin_email = 'admin0028@gmail.com';
            $admin_tel = '010-1111-1111';
            $admin_register_date = '2022-08-12 09:00';
            $admin_level = '999';

            $sql_insert_admin = "INSERT INTO members VALUES ('$admin_id','$admin_password','$admin_name', '$admin_gender', '$admin_email', '$admin_tel', '$admin_register_date', $admin_level)";

            $result = mysqli_query($con, $sql_insert_admin);
            if ($result) {
                echo "<script>alert('관리자정보를 생성했습니다')</script>";
            } else {
                echo "<script>alert('관리자정보를 생성하는 데 실패했습니다.<br>별도로 회원가입을 진행해주세요')</script>";
            }
        }
    }
?>