<?php
    // 데이터베이스 연결
    include_once $_SERVER['DOCUMENT_ROOT']."/MIRAEBOOKS/db/db_create_statement.php";
    // GET 방식으로 mode값을 받아서 그에 따라 실행한 구문을 달리함
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'] ;
        switch($mode) {
            // 회원 가입을 통해서 회원 정보를 등록할 때 사용
            case 'member_insert' : 
                if (isset($_POST['member_id']) && isset($_POST['member_password']) && isset($_POST['member_password_confirm']) && isset($_POST['member_name']) && isset($_POST['member_gender']) && isset($_POST['member_gender']) && isset($_POST['member_gender']) && isset($_POST['member_gender'])  ) {
                    $member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    $member_password = mysqli_real_escape_string($con, $_POST['member_password']);
                    $member_name = mysqli_real_escape_string($con, $_POST['member_name']);
                    $member_gender = mysqli_real_escape_string($con, $_POST['member_gender']);
                    $member_email = mysqli_real_escape_string($con, $_POST['member_email']);
                    $member_tel = mysqli_real_escape_string($con, $_POST['member_tel']);
                    date_default_timezone_set('Asia/Seoul');
                    $member_register_date = date('Y-m-d H:i');
                    $register_info = "member_id={$member_id}&member_name={$member_name}&member_gender={$member_gender}&member_email={$member_email}&member_tel={$member_tel}";

                    $member_password = password_hash($member_password, PASSWORD_DEFAULT);
                    $sql_check_id = "SELECT * FROM members WHERE member_id = '$member_id'";
                    $recond_set = mysqli_query($con,  $sql_check_id);
                    if (mysqli_num_rows($recond_set) > 0) {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/register.php" method="post" name="register_error">
                    <input type="hidden" name="error" value="이미 사용중인 아이디 입니다">
                    <input type="hidden" name="member_id" value="<?=$member_id?>">
                    <input type="hidden" name="member_name" value="<?=$member_name?>">
                    <input type="hidden" name="member_gender" value="<?=$member_gender?>">
                    <input type="hidden" name="member_email" value="<?=$member_email?>">
                    <input type="hidden" name="member_tel" value="<?=$member_tel?>">
                    </form>
                    <script>
                        document.register_error.submit();
                    </script>
                    <?php
                        exit();
                    } else {
                        $sql_insert = "INSERT INTO members (member_id, member_password, member_name, member_gender, member_email, member_tel, member_register_date) VALUES ('$member_id','$member_password','$member_name', '$member_gender', '$member_email', '$member_tel', '$member_register_date')";
                        $result = mysqli_query($con, $sql_insert);

                        if($result){
                            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/login.php');
                            exit();
                        } else {
                        ?>    
                            <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/register.php" method="post" name="register_error">
                                <input type="hidden" name="error" value="회원가입을 진행하던 도중 오류가 발생했습니다">
                                <input type="hidden" name="member_id" value="$member_id">
                                <input type="hidden" name="member_name" value="$member_name">
                                <input type="hidden" name="member_gender" value="$member_gender">
                                <input type="hidden" name="member_email" value="$member_email">
                                <input type="hidden" name="member_tel" value="$member_tel">
                                </form>
                                <script>
                                    document.register_error.submit();
                                    return;
                                </script>
                            <?php
                            exit();
                        }
                    }        
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 아이디를 찾을 때 사용
            case 'find_id' : 
                if (isset($_POST['member_name']) && isset($_POST['member_email']) && isset($_POST['member_tel'])) {
                    $member_name = mysqli_real_escape_string($con, $_POST['member_name']);
                    $member_email = mysqli_real_escape_string($con, $_POST['member_email']);
                    $member_tel = mysqli_real_escape_string($con, $_POST['member_tel']);

                    $sql_select_id = "SELECT * FROM members WHERE member_name = '$member_name' AND member_email = '$member_email' AND member_tel = '$member_tel'";
                    $record_set = mysqli_query($con, $sql_select_id);
                    if (mysqli_num_rows($record_set) == 1) {
                        $row = mysqli_fetch_array($record_set);
                        $member_id = $row['member_id'];
                        $member_name = $row['member_name'];
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_id.php" name="found_id" method="post">
                        <input type="hidden" name="member_id" value="<?=$member_id?>">
                        <input type="hidden" name="member_name" value="<?=$member_name?>">
                    </form>
                    <script>
                        document.found_id.submit();
                    </script>
                    <?php
                        exit();
                    } else {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_id.php" name="find_id_error" method="post">
                        <input type="hidden" name="error" value='해당되는 회원정보를 찾을 수 없습니다'>
                        <input type="hidden" name="member_name" value='<?=$member_name?>'>
                        <input type="hidden" name="member_email" value='<?=$member_email?>'>
                        <input type="hidden" name="member_tel" value='<?=$member_tel?>'>
                    </form>
                    <script>
                        document.find_id_error.submit();
                    </script>
                    <?php
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 비밀번호를 찾을 때 사용
            case 'find_pw' : 
                if (isset($_POST['member_id']) && isset($_POST['member_name']) && isset($_POST['member_email']) && isset($_POST['member_tel'])) {
                    $member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    $member_name = mysqli_real_escape_string($con, $_POST['member_name']);
                    $member_email = mysqli_real_escape_string($con, $_POST['member_email']);
                    $member_tel = mysqli_real_escape_string($con, $_POST['member_tel']);

                    $sql_select_id = "SELECT * FROM members WHERE member_id = '$member_id' AND member_name = '$member_name' AND member_email = '$member_email' AND member_tel = '$member_tel'";
                    $record_set = mysqli_query($con, $sql_select_id);
                    if (mysqli_num_rows($record_set) == 1) {
                        $row = mysqli_fetch_array($record_set);
                        $member_id = $row['member_id'];
                        $member_name = $row['member_name'];
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/find_pw.php?success=1&member_id='.$member_id.'');
                    ?>
                        <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" name="find_pw_success" method="post">
                            <input type="hidden" name="success" value=1>
                            <input type="hidden" name="member_id" value='<?=$member_id?>'>
                        </form>
                        <script>
                            document.find_pw_success.submit();
                        </script>
                    
                    <?php
                        exit();
                    } else {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" name="find_pw_error" method="post">
                        <input type="hidden" name="error" value='해당되는 회원정보를 찾을 수 없습니다'>
                        <input type="hidden" name="member_id" value='<?=$member_id?>'>
                        <input type="hidden" name="member_name" value='<?=$member_name?>'>
                        <input type="hidden" name="member_email" value='<?=$member_email?>'>
                        <input type="hidden" name="member_tel" value='<?=$member_tel?>'>
                    </form>
                    <script>
                        document.find_pw_error.submit();
                    </script>
                    <?php
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 비밀번호를 새로 변경할 때 사용
            case 'member_update_password' : 
                if (isset($_POST['member_id']) && isset($_POST['member_password']) && isset($_POST['member_password_confirm'])) {
                    $member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    $member_password = mysqli_real_escape_string($con, $_POST['member_password']);
                    $member_password = password_hash($member_password, PASSWORD_DEFAULT);

                    $sql_update_pw = "UPDATE members SET member_password = '$member_password'";
                    $result = mysqli_query($con, $sql_update_pw);
                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/login/find_pw.php?success=2');
                    } else {
                    ?>
                        <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/find_pw.php" name="set_new_passwrod_error" method="post">
                            <input type="hidden" name="error" value='비밀번호를 변경하는 과정에서 오류가 발생했습니다'>
                            <input type="hidden" name="member_id" value='<?=$member_id?>'>
                            <input type="hidden" name="member_name" value='<?=$member_name?>'>
                            <input type="hidden" name="member_email" value='<?=$member_email?>'>
                            <input type="hidden" name="member_tel" value='<?=$member_tel?>'>
                        </form>
                        <script>
                            document.set_new_passwrod_error.submit();
                        </script>
                    <?php
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 회원 정보를 수정할 때 사용
            case 'member_update' :
                if (isset($_POST['member_id']) && isset($_POST['member_password']) && isset($_POST['member_password_confirm']) &&  isset($_POST['member_name']) && isset($_POST['member_gender']) && isset($_POST['member_email']) && isset($_POST['member_tel'])) {
                    $member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    $member_password = mysqli_real_escape_string($con, $_POST['member_password']);
                    $member_password_confirm = mysqli_real_escape_string($con, $_POST['member_password_confirm']);
                    $member_name = mysqli_real_escape_string($con, $_POST['member_name']);
                    $member_gender = mysqli_real_escape_string($con, $_POST['member_gender']);
                    $member_email = mysqli_real_escape_string($con, $_POST['member_email']);
                    $member_tel = mysqli_real_escape_string($con, $_POST['member_tel']);

                    $member_password = password_hash($member_password, PASSWORD_DEFAULT);
                    $sql_update = "UPDATE members SET member_password='$member_password', member_name='$member_name', member_gender='$member_gender', member_email='$member_email', member_tel='$member_tel'  WHERE member_id='$member_id'";
                    $result = mysqli_query($con, $sql_update);

                    if ($result) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/member/mypage_edit.php?success');
                        exit();
                    } else {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/mypage_edit.php" name="modify_error" method="post">
                        <input type="hidden" name="error" value='회원 정보 수정에 실패했습니다'>
                    </form>
                    <script>
                        document.modify_error.submit();
                    </script>
                    <?php
                        exit();
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 회원이 스스로 탈퇴할 때 사용
            case 'member_withdrawal' :
                if (isset($_POST['member_id']) && isset($_POST['member_password'])) {
                    $member_id = $_POST['member_id'];
                    $member_password = $_POST['member_password'];

                    $sql_check_id = "SELECT * FROM members WHERE member_id = '$member_id'";
                    $record_set = mysqli_query($con, $sql_check_id);
                    if (mysqli_num_rows($record_set) == 1) {
                        $row = mysqli_fetch_array($record_set);
                        if (password_verify($member_password, $row['member_password'])) {
                            $sql_delete_member = "DELETE FROM members WHERE member_id = '$member_id'";
                            $result = mysqli_query($con, $sql_delete_member);
                            if ($result) {
                                header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/member/mypage_withdrawal.php?success');
                            exit();
                            }
                        } else {
                        ?>
                        <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/mypage_withdrawal.php" name="member_withdrawal_error1" method="post">
                            <input type="hidden" name="error" value='비밀번호가 틀렸습니다'>
                        </form>
                        <script>
                            document.member_withdrawal_error1.submit();
                        </script>
                        <?php
                            exit();
                        }
                    } else {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/member/mypage_withdrawal.php" name="member_withdrawal_error2" method="post">
                        <input type="hidden" name="error" value='해당 아이디를 찾을 수 없습니다'>
                    </form>
                    <script>
                        document.member_withdrawal_error2.submit();
                    </script>
                    <?php
                        exit();
                    }
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
            // 관리자가 회원을 탈퇴시킬 때 사용
            case 'admin_withdrawal' :
                session_start();
                if (isset($_SESSION['user_level'])) {
                    $user_level = $_SESSION['user_level'];
                    
                    if ($_SESSION['user_level'] != 999) {
                        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=admin');
                        exit();
                    } 

                    if (isset($_POST['checkbox'])) {
                        for ($i = 0; $i < count($_POST['checkbox']); $i++) {
                            $member_id = $_POST['checkbox'][$i];
                            $sql_delete_member = "DELETE FROM members WHERE member_id = '$member_id'";
                            $result = mysqli_query($con, $sql_delete_member);
                        }
                        if ($result) {
                                echo "
                                    <script>
                                        alert('총 ".count($_POST['checkbox'])."명의 회원이 탈퇴되었습니다')
                                        history.go(-1);
                                    </script>
                                ";
                            exit();
                            }
                    } else {
                        echo "
                            <script>
                                alert('탈퇴시킬 회원을 선택해주세요')
                                history.go(-1);
                            </script>
                        ";
                    }
                }
                break;
            // 로그인 할 때 사용
            case 'login' :
                if (isset($_POST['member_id']) && isset($_POST['member_password'])) {
                    $member_id = mysqli_real_escape_string($con, $_POST['member_id']);
                    $member_password = mysqli_real_escape_string($con, $_POST['member_password']);
                    $member_info = "member_id={$member_id}";

                    $sql_same_id = "SELECT * FROM members WHERE member_id = '$member_id'";
                    $record_set = mysqli_query($con, $sql_same_id);
                    if (mysqli_num_rows($record_set) == 1) {
                        $row = mysqli_fetch_array($record_set);
                        if (password_verify($member_password, $row['member_password'])) {
                            session_start();
                            $_SESSION['user_id'] = $row['member_id'];
                            $_SESSION['user_name'] = $row['member_name'];
                            $_SESSION['user_level'] = $row['member_level'];
                            if (isset($_POST['save_id']) && $_POST['save_id'] == 'on') {
                                setcookie('user_id', $row['member_id'], time() + (86400 * 7), "/");
                            } else {
                                setcookie('user_id', $row['member_id'], time() - (86400 * 7), "/");
                            }
                            header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?');
                            exit();
                        } else {
                        ?>
                        <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" name="login_error1" method="post">
                            <input type="hidden" name="error" value='비밀번호가 틀렸습니다'>
                            <input type="hidden" name="member_id" value='<?=$member_id?>'>
                        </form>
                        <script>
                            document.login_error1.submit();
                        </script>
                        <?php
                            exit();
                        }
                    } else {
                    ?>
                    <form action="http://<?=$_SERVER['HTTP_HOST'];?>/MIRAEBOOKS/php/login/login.php" name="login_error2" method="post">
                        <input type="hidden" name="error" value='등록된 아이디가 아닙니다'>
                        <input type="hidden" name="member_id" value='<?=$member_id?>'>
                    </form>
                    <script>
                        document.login_error2.submit();
                    </script>
                    <?php
                        exit();
                    }
                    mysqli_close($con);
                } else {
                    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
                    exit();
                }
                break;
        }
    } else {
        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
        exit();
    }
?>