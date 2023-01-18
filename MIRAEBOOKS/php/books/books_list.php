<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/ebook.css">
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }
            
            if (isset($_GET['lang']) && isset($_GET['direction'])) {
                $lang = $_GET['lang'];
                $direction = $_GET['direction'];
                $direction = ($direction == 'DESC') ? 'ASC' : 'DESC';
            } else {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                exit();
            }

            $sort = isset($_GET['sort']) ? $_GET['sort'] : 0;
            switch($sort) {
                case 0: 
                    $sort = 'ebook_hit'; 
                    break;
                case 1: 
                    $sort = 'ebook_id'; 
                    break;
                case 2: 
                    $sort = 'ebook_title'; 
                    break;
                case 3: 
                    $sort = 'ebook_author'; 
                    break;
            }
        ?>
    </header>
    <main>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_books.php";
        ?>
        <section id="list_section">
            <ul id="sort_list">
                <li>
                <?php
                    if ($sort == 'ebook_hit') {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=0&direction=".$direction."' class='active'>조회순</a>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=0&direction=".$direction."'>조회순</a>";
                    }
                ?>
                </li>
                <li>
                <?php
                    if ($sort == 'ebook_id') {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=1&direction=".$direction."' class='active'>등록순</a>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=1&direction=".$direction."'>등록순</a>";
                    }
                ?>
                </li>
                <li>
                <?php
                    if ($sort == 'ebook_title') {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=2&direction=".$direction."' class='active'>제목순</a>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=2&direction=".$direction."'>제목순</a>";
                    }
                ?>
                </li>
                <li>
                <?php
                    if ($sort == 'ebook_author') {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=3&direction=".$direction."' class='active'>저자명순</a>";
                    } else {
                        echo "<a href='http://".$_SERVER['HTTP_HOST']."/MIRAEBOOKS/php/books/books_list.php?lang=".$lang."&sort=3&direction=".$direction."'>저자명순</a>";
                    }
                ?>
                </li>
            </ul>
            <ul id="ebook_list">
            <?php
                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
            
                $sql_select_ebook = "SELECT * FROM ebook WHERE (ebook_lang = '$lang') AND ebook_open = 1 ORDER BY ".$sort." ".$direction."";
                $record_set = mysqli_query($con, $sql_select_ebook);
                $number = 1;
                while ($row = mysqli_fetch_array($record_set)) {
                    $ebook_id = $row['ebook_id'];
                    $ebook_title = $row['ebook_title'];
                    $ebook_author = $row['ebook_author'];
                    $ebook_bookcover_name = $row['ebook_bookcover_name'];
                    $ebook_bookcover_path = $row['ebook_bookcover_path'];
                    $ebook_hit = $row['ebook_hit'];
            ?>
                <li id="ebook">
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_view&ebook_id=<?=$ebook_id?>">
                        <img src="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>" alt="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>">
                        <?php
                            if ($number < 6) {
                                echo "<p id='rank'>".$number."</p>";
                            }
                            if ($lang == 'en') {
                                echo "<style>#ebook_title, #ebook_author {font-family: 'Playfair Display', serif;}</style>";
                            } else if ($lang == 'jp') {
                                echo "<style>#ebook_title, #ebook_author {font-family: 'Noto Serif JP', serif;}</style>";
                            } else {
                                echo "<style>#ebook_title, #ebook_author {font-family: 'Noto Serif KR', serif;}</style>";
                            }
                        ?>
                        <p id="ebook_title"><?=$ebook_title?></p>
                        <p id="ebook_author"><?=$ebook_author?></p>
                    </a>
                    <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_download&ebook_id=<?=$ebook_id?>" id="download">다운로드</a>
                    <span id="ebook_hit"><?=$ebook_hit?></span>
                </li>
            <?php
                    $number++;     
                }
            ?>
            </ul>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>