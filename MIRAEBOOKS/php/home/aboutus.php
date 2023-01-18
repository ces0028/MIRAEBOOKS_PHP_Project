<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css?after">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/home.css?after">
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";
        ?>
    </header>
    <main>
        <section>
            <div id="home_header">
                <h2>ABOUT US</h2>
            </div>
            <div id="aboutus_container">
                <span>미래북스</span>는 저작권이 만료된 작품을 누구나 자유롭게 볼 수 있도록 만들어진 사이트입니다.<br>
                현재 영어, 일본어, 한국어, 총 3가지 언어로 된 작품들이 업로드되어 있습니다.<br>

                저작권이 만료되어 있기 때문에, 미래북스에 업로드된 파일들은 유료, 무료 등과 관련없이 자유롭게 복제, 재배포, 공유가 가능합니다.<br>
                이 과정에서 미래북스의 허락이 필요하거나 하지 않기 때문에 언제든지 자유롭게 이용하셔도 됩니다.<br>

                미래북스에 업로드된 자료들은 아래 사이트에서 자유롭게 공유 가능하다는 걸 확인하고 가져왔습니다.<br>
            </div>
            <table id="source_table">
                <tr>
                    <th>ENGLISH</th>
                    <td><a href="https://www.gutenberg.org/" id="en">Project Gutenberg</a></td>
                </tr>
                <tr>
                    <th>JAPANESE</th>
                    <td><a href="https://www.aozora.gr.jp/" id="jp">青空文庫</a></td>
                </tr>
                <tr>
                    <th>KOREAN</th>
                    <td><a href="https://gongu.copyright.or.kr/gongu/main/main.do" id="kr">한국저작권위원회 공유마당</a></td>
                </tr>
            </table>
            </div>
        </section>
    </main>
    <footer>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/footer.php";
        ?>
    </footer>
</body>
</html>