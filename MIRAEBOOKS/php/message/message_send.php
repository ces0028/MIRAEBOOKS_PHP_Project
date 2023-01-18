<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIRAEBOOKS</title>
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/common.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/css/message.css">
    <script src="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/js/message_js.js"></script>
</head>
<body>
    <header>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/header.php";

            if (!$user_id) {
                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php?alert=login');
                exit();
            }
            
            $type = "";
            if (isset($_GET['type']) && isset($_GET['message_id'])) {
                $type = 'reply';
                $title = 'REPLY MESSAGE';
                $message_id = $_GET['message_id'];

                include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";

                $sql_select_message = "SELECT * FROM message WHERE message_id = '$message_id'";
                $record_set = mysqli_query($con, $sql_select_message);
                $row = mysqli_fetch_array($record_set);
                $message_send_id = $row['message_send_id'];
                $message_title = $row['message_title'];
                $message_title = "RE: ".$message_title;
                $message_content = $row['message_content'];
                $message_register_date = $row['message_register_date'];
                $message_content = '> '.$message_content;
                $message_content = $message_content."\n ".$message_register_date;
                $message_content = str_replace("\n", "\n>", $message_content);
                $message_content = "\n\n\n--------------------------------------------------------------------------------------\n".$message_content;
            } else {
                $title = 'COMPOSE MESSAGE';
            }
        ?>
    </header>
    <main>
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/php/common/nav_message.php";
        ?>
        <section>
            <div id="main_header">
                <h2>MESSAGE > <?=$title?></h2>
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
            <form name="message_send_form" action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/server/message_server.php?mode=message_insert" method="post">
                <table id="compose_message">
                <?php
                    if ($user_level == 999 && !$type) {
                ?>
                    <tr>
                        <th>전체 보내기</th>
                        <td><input type="checkbox" name="checkbox" id="checkbox" onclick="sendAll()"></td>
                    </tr>
                <?php
                    }
                ?>
                    <tr>
                        <th>보내는 사람</th>
                        <td><input type="text" id="message_send_id" value="<?=$user_name?>(<?=$user_id?>)" readonly></td>
                        <input type="hidden" name="message_send_id" value="<?=$user_id?>" readonly>
                    </tr>
                    <tr class="receiver">
                        <th>받는 사람</th>
                    <?php
                        if ($type) {
                            echo "<td><input type='text' name='message_receive_id' id='message_receive_id' value='".$message_send_id."'></td>";
                        } else {
                            echo "<td><input type='text' name='message_receive_id' id='message_receive_id'></td>";
                        }
                    ?>
                    </tr>
                    <tr>
                        <th>제목</th>
                    <?php
                        if ($type) {
                            echo "<td><input type='text' name='message_title' id='message_title' value='".$message_title."'></td>";
                        } else {
                            echo "<td><input type='text' name='message_title' id='message_title'></td>";
                        }
                    ?>
                    </tr>
                    <tr>
                        <th>내용</th>
                    <?php
                        if ($type) {
                            echo "<td><textarea name='message_content' id='message_content'>".$message_content."</textarea></td>";
                        } else {
                            echo "<td><textarea name='message_content' id='message_content'></textarea></td>";
                        }
                    ?>
                    </tr>
                </table>
                <div id="buttons">
                    <input type="button" id="send_button" onclick="checkSendMessage()" value="작성">
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