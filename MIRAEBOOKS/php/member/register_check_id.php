<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
    $id = "";
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($con, $_GET['id']);
    }
    if (empty($id)) {
        $message = "아이디를 먼저 입력해주세요";
    } else {
        $sql_check_id = "SELECT *  FROM members WHERE member_id = '$id'";
        $record_set = mysqli_query($con, $sql_check_id);

        if (mysqli_num_rows($record_set) == 1) {
            $message = "현재 사용중인 아이디입니다";
        } else {
            $message = "사용 가능한 아이디입니다";
        }
        mysqli_close($con);  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            text-align:center;
            padding: 0 5px;
            border-left: solid 5px rgb(219,187,104);
            border-right: solid 5px rgb(219,187,104);
        }
        p {
            font-weight: 100;
        }
        #close_button {
            cursor:pointer;
        }
        input {
            background-color: #40496d;
            width: 90px;
            height: 35px;
            border: none;
            border-radius : 10px;
            font-weight : 900;
            color: white;
        }
    </style>
</head>
<body>
    <h3>아이디 중복체크</h3>
    <p>
        <?=$message?>
    </p>
    <input type="button" value="닫기" id="close_button" onclick="javascript:self.close()">
</body>
</html>