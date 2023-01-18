<?php
    include $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_connector.php";
    include $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_table.php";
    include $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_procedure.php";
    include $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_admin.php";

    /* CREATE TABLE */
    create_table($con, 'members');
    create_table($con, 'ebook');
    create_table($con, 'board');
    create_table($con, 'board_reply');
    create_table($con, 'qna_board_question');
    create_table($con, 'qna_board_answer');
    create_table($con, 'request_board');
    create_table($con, 'message');
    
    /* CREATE ADMIN DATA */
    create_admin($con, 'admin0028');

    /* CREATE PROCEDURE */
    create_procedure($con, 'member_procedure');
    create_procedure($con, 'ebook_procedure');
    create_procedure($con, 'notice_board_procedure');
    create_procedure($con, 'free_board_procedure');
    create_procedure($con, 'question_board_procedure');
    create_procedure($con, 'answer_board_procedure');
    create_procedure($con, 'request_board_procedure');
    create_procedure($con, 'board_reply_procedure');
    create_procedure($con, 'message_procedure');
?>