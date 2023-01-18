<h2 id="slideshow_name_tag">MOST POPULAR</h2>
<div class="slideshow first_slideshow">
    <div class="slideshow_slide first_slideshow_slide">
    <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
        $scale = 10;
        $sql_sort_by_hit = "SELECT * FROM ebook WHERE ebook_open = 1 ORDER BY ebook_hit DESC LIMIT 0, $scale";
        $record_set = mysqli_query($con, $sql_sort_by_hit);
        while ($row = mysqli_fetch_array($record_set)) {
            $ebook_id = $row['ebook_id'];
            $ebook_content_name = $row['ebook_content_name'];
            $ebook_content_path = $row['ebook_content_path'];
            $ebook_bookcover_name = $row['ebook_bookcover_name'];
            $ebook_bookcover_path = $row['ebook_bookcover_path'];
    ?>
        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_view&ebook_id=<?=$ebook_id?>">
            <img src="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>" alt="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>">
        </a>
    <?php
        }
    ?>
    </div>
    <div class="slideshow_nav first_slideshow_nav">
        <a href="#" id="first_prev">
            <i class="fa-solid fa-circle-chevron-left"></i>
        </a>
        <a href="#" id="first_next">
            <i class="fa-solid fa-circle-chevron-right"></i>
        </a>
    </div>
    <div class="slideshow_indicator first_slideshow_indicator">
        <a href="#" class="active"><i class="fa-solid fa-circle"></i></a>
        <?php
        for ($i = 0; $i < $scale - 1; $i++) {
            echo "<a href='#'><i class='fa-solid fa-circle'></i></a>";
        }
        ?>
    </div>
</div>
<h2 id="slideshow_name_tag">NEW ARRIVALS</h2>
<div class="second_slideshow slideshow">
    <div class="slideshow_slide second_slideshow_slide">
    <?php
        $scale = 10;
        include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
        $sql_sort_by_hit = "SELECT * FROM ebook WHERE ebook_open = 1 ORDER BY ebook_id DESC LIMIT 0, $scale";
        $record_set = mysqli_query($con, $sql_sort_by_hit);
        while ($row = mysqli_fetch_array($record_set)) {
            $ebook_id = $row['ebook_id'];
            $ebook_content_name = $row['ebook_content_name'];
            $ebook_content_path = $row['ebook_content_path'];
            $ebook_bookcover_name = $row['ebook_bookcover_name'];
            $ebook_bookcover_path = $row['ebook_bookcover_path'];
    ?>
        <a href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/books_server.php?mode=ebook_view&ebook_id=<?=$ebook_id?>">
            <img src="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>" alt="http://<?=$_SERVER['HTTP_HOST']?><?=$ebook_bookcover_path?><?=$ebook_bookcover_name?>">
        </a>
    <?php
        }
    ?>    
    </div>
    <div class="slideshow_nav">
        <a href="#" id="second_prev">
            <i class="fa-solid fa-circle-chevron-left"></i>
        </a>
        <a href="#" id="second_next">
            <i class="fa-solid fa-circle-chevron-right"></i>
        </a>
    </div>
    <div class="slideshow_indicator second_slideshow_indicator">
        <a href="#" class="active"><i class="fa-solid fa-circle"></i></a>
        <?php
        for ($i = 0; $i < $scale - 1; $i++) {
            echo "<a href='#'><i class='fa-solid fa-circle'></i></a>";
        }
        ?>
    </div>
</div>