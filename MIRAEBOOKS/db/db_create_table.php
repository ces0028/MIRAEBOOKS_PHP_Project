<?php
    function create_table($con, $table_name) {
        // 테이블이 이미 만들어져있는지를 확인
        $table_flag = false;
        // 데이터베이스에 만들어진 모든 테이블을 가져옴
        $sql_show = "SHOW TABLES FROM miraebooksdb" ;
        $result = mysqli_query($con, $sql_show) or die('THERE IS NO TABLE'.mysqli_errno());
        while($row = mysqli_fetch_assoc($result)) {
            // 만들고자 하는 테이블명과 데이터베이스에 이미 만들어진 테이블명을 비교
            if ($row['Tables_in_miraebooksdb'] == "$table_name") {
                $table_flag = true;
                break;
            }
        }

        // 테이블이 없다면 데이터베이스를 새로 생성
        if ($table_flag == false) {
            switch($table_name) {
                // 회원 테이블
                case 'members' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS members(
                            member_id CHAR(20) NOT NULL,
                            member_password VARCHAR(255) NOT NULL,
                            member_name CHAR(10) NOT NULL,
                            member_gender CHAR(6) NOT NULL,
                            member_email CHAR(50) NOT NULL,
                            member_tel CHAR(13) NOT NULL,
                            member_register_date CHAR(20) NOT NULL,
                            member_level INT NOT NULL DEFAULT 1,
                            PRIMARY KEY (member_id)
                        )";
                    break;
                // ebook 테이블
                case 'ebook' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS ebook(
                            ebook_id INT NOT NULL AUTO_INCREMENT,
                            ebook_lang CHAR(2) NOT NULL, 
                            ebook_title CHAR(50) NOT NULL,
                            ebook_author CHAR(50) NOT NULL,
                            ebook_bookcover_name CHAR(50) NOT NULL,
                            ebook_bookcover_path VARCHAR(255) NOT NULL,
                            ebook_content_name CHAR(50) NOT NULL,
                            ebook_content_path VARCHAR(255) NOT NULL,
                            ebook_file_name CHAR(50) NOT NULL,
                            ebook_file_path VARCHAR(255) NOT NULL,
                            ebook_hit INT NOT NULL DEFAULT 0,
                            ebook_open INT NOT NULL DEFAULT 1,
                            PRIMARY KEY (ebook_id)
                        )";
                    break;
                // 게시판(공지사항, 자유게시판) 테이블
                case 'board' :  
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS board(
                            board_id INT NOT NULL AUTO_INCREMENT,
                            board_type CHAR(10) NOT NULL,
                            board_member_id CHAR(20) NOT NULL,
                            board_title CHAR(100) NOT NULL,
                            board_content TEXT NOT NULL,
                            board_register_date CHAR(20) NOT NULL,
                            board_hit INT NOT NULL DEFAULT 0,
                            board_file_name VARCHAR(255),
                            board_file_type VARCHAR(50),
                            board_file_path VARCHAR(255),
                            board_file_saved_name VARCHAR(255),
                            PRIMARY KEY (board_id)
                        )";
                    break;
                // 게시판 댓글 테이븛
                case 'board_reply' : 
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS board_reply (
                            reply_id INT NOT NULL AUTO_INCREMENT,
                            reply_post INT NOT NULL,
                            reply_post_member_id CHAR(20) NOT NULL,
                            reply_member_id CHAR(20) NOT NULL,
                            reply_content text NOT NULL,
                            reply_register_date CHAR(20) NOT NULL,
                            PRIMARY KEY (reply_id)
                        );";
                    break;
                // 문의 게시판 게시물 테이블
                case 'qna_board_question' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS qna_board_question (
                            qna_id INT NOT NULL AUTO_INCREMENT,
                            qna_member_id CHAR(20) NOT NULL,
                            qna_question_title char(100) NOT NULL,
                            qna_question_content TEXT NOT NULL,
                            qna_question_register_date CHAR(20) NOT NULL,
                            PRIMARY KEY (qna_id)
                        )";
                    break;
                // 문의 게시판 답변 테이블
                case 'qna_board_answer' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS qna_board_answer (
                            question_qna_id INT NOT NULL,
                            qna_answer TEXT,
                            qna_answer_register_date CHAR(20),
                            PRIMARY KEY (question_qna_id)
                        )";
                    break;
                // 요청 게시판 테이블
                case 'request_board' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS request_board (
                            request_id INT NOT NULL AUTO_INCREMENT,
                            request_member_id CHAR(20) NOT NULL,
                            request_lang CHAR(3) NOT NULL,
                            request_title CHAR(100) NOT NULL,
                            request_author CHAR(50) NOT NULL,
                            request_register_date CHAR(20) NOT NULL,
                            request_result INT DEFAULT 0,
                            PRIMARY KEY (request_id)
                        )";
                    break;
                // 메시지 테이블
                case 'message' :
                    $sql_create = "
                        CREATE TABLE IF NOT EXISTS message (
                            message_id INT NOT NULL AUTO_INCREMENT,
                            message_send_id CHAR(20) NOT NULL,
                            message_receive_id CHAR(20) NOT NULL,
                            message_title CHAR(100) NOT NULL,
                            message_content TEXT NOT NULL, 
                            message_register_date CHAR(20),
                            PRIMARY KEY (message_id)
                        )";
                    break;    
                default : 
                    echo "<script>alert('해당 테이블을 찾을 수 없습니다')</script>";
                    break;
            }
            
            // 상기에서 지정한 쿼리문을 작동
            $result = mysqli_query($con, $sql_create) or die('FAILURE TO CREATE TABLE'.mysqli_errno());
            if ($result) {
                echo "<script>alert('SUCCESS TO CREATE TABLE". $sql_create."')</script>";
            } else {
                echo "<script>alert('FAILURE TO CREATE TABLE". $sql_create."')</script>";
            }
        }
    }
?>