<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/ebook.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/admin_js.js?after"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if ($user_level != 999) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                exit();
            } 
        ?>
    </header>
    <main>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_admin.php";
        ?>
        <section id="insert_section">
            <div id="main_header">
                <h2>ADMIN > BOOKS > ADD</h2>
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
            <form name="ebook_insert_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_insert" method="post" enctype="multipart/form-data">
                <table id="ebook_insert_table">
                    <tr>
                        <th>언어</th>
                        <td>
                            <select name="ebook_lang" id="ebook_lang">
                                <option value="none">선택</option>
                                <option value="en">영어</option>
                                <option value="jp">일본어</option>
                                <option value="kr">한국어</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="ebook_title" id="ebook_title" maxlength="50"></td>
                    </tr>
                    <tr>
                        <th>저자</th>
                        <td><input type="text" name="ebook_author" id="ebook_author" maxlength="30"></td>
                    </tr>
                    <tr>
                        <th>표지</th>
                        <td><input type="file" name="ebook_bookcover" id='ebook_bookcover'></td>
                    </tr>
                    <tr>
                        <th>링크</th>
                        <td><input type="file" name="ebook_content" id='ebook_content'></td>
                    </tr>
                    <tr>
                        <th>파일</th>
                        <td><input type="file" name="ebook_file" id='ebook_file'></td>
                    </tr>
                </table>
                <div id="buttons">
                    <input type="button" id="request_button" onclick="checkInsertEbook()" value="추가">
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/admin/admin_books.php" id="buttona"><input type="button" id="list_button" value="목록"></a>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>