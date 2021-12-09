<?php
include_once('../db/db_connector.php');

if (isset($_SESSION['session_id'])) {

    $id = sql_escape($con, $_SESSION['session_id']);

    if (empty($id)) {
        mysqli_close($con);
        header("location: login.php?error=empty_id");
        exit;
    } else {
        // 아이디가 실제 회원인지 확인
        $sql = "SELECT * FROM user WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        // 아이디가 존재한다면 넘어온 값 점검
        if (mysqli_num_rows($result) > 0) {
            if (
                isset($_POST["password"]) && isset($_POST["email1"]) && isset($_POST["email2"]) && isset($_POST['mobile1'])
                && isset($_POST['mobile2']) && isset($_POST['mobile3']) && isset($_POST['gender'])
            ) {

                // 인젝션 방어
                $password = sql_escape($con, $_POST['password']);
                $email1 = sql_escape($con, $_POST['email1']);
                $email2 = sql_escape($con, $_POST['email2']);
                $mobile1 = sql_escape($con, $_POST['mobile1']);
                $mobile2 = sql_escape($con, $_POST['mobile2']);
                $mobile3 = sql_escape($con, $_POST['mobile3']);
                $gender = sql_escape($con, $_POST['gender']);

                // 공백 점검
                if (empty($password)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=패스워드가 비어있음");
                    exit;
                } else if (empty($email1)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=email1이 비어있음");
                    exit;
                } else if (empty($email2)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=email2가 비어있음");
                    exit;
                } else if (empty($mobile1)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=mobile1가 비어있음");
                    exit;
                } else if (empty($mobile2)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=mobile2가 비어있음");
                    exit;
                } else if (empty($mobile3)) {
                    mysqli_close($con);
                    header("location: login.php.php?error=mobile3가 비어있음");
                    exit;
                } else {
                    // 정규표현식 체크
                    if (
                        preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/", $password) && preg_match("/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*$/i", $email1)
                        && preg_match("/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i", $email2) && preg_match("/^01([0|1|6|7|8|9])$/", $mobile1)
                        && preg_match(" /^([0-9]{3,4})$/", $mobile2) && preg_match(" /^([0-9]{3,4})$/", $mobile3)
                    ) {
                        // 데이터베이스에 넣기

                        // 입력한 비밀번호를 MySQL password() 함수를 이용해 암호화해서 가져옴
                        $sql = "SELECT UNHEX('" . $row['password'] . "') as password;";
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $unhex_password = $row['password'];

                        if($unhex_password === $password){
                            header('location: login.php?error=used_password');
                            exit;
                        }else{
                            // 입력한 비밀번호를 MySQL password() 함수를 이용해 암호화해서 가져옴
                            $sql = " SELECT HEX('$password') AS pass "; 
                            $result = mysqli_query($con, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $password = $row['pass'];
                        }

                        $sql = " UPDATE user SET password = '$password', 
                                                email1 = '$email1', 
                                                email2 = '$email2', 
                                                mobile1 = '$mobile1', 
                                                mobile2 = '$mobile2', 
                                                mobile3 = '$mobile3' , 
                                                gender = '$gender' WHERE id = '$id' ";

                        $result = mysqli_query($con, $sql) or die('fail' . mysqli_error($con));
                        if ($result) {
                            echo "<script>alert('수정이 완료되었습니다')</script>";
                            echo "<script>location.replace('./mypage_user.php')</script>";
                            mysqli_close($con);
                            return;
                        }
                    } else {
                        echo "<script>alert('형식에 맞지 않습니다');</script>";
                        echo "<script>location.replace('./mypage_user.php');</script>";
                        mysqli_close($con);
                        exit;
                    }
                }
            } else {
                echo "<script>alert('값을 입력하세요');</script>";
                echo "<script>location.replace('./mypage_user.php');</script>";
                mysqli_close($con);
                exit;
            }
        } else {
            echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
            echo "<script>location.replace('./login.php');</script>";
            mysqli_close($con);
            exit;
        }
    }
} else {
    echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
    echo "<script>location.replace('./login.php');</script>";
    mysqli_close($con);
    exit;
}
mysqli_close($con);
?>
