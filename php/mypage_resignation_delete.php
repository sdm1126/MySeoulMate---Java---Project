<!-- 세션 체크 -->
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


            // 삭제 query문 실행
            $sql = "DELETE  FROM user WHERE id = '$id'";
            $result = mysqli_query($con, $sql);

            // 성공시 세션 해제
            if ($result) {
                unset($_SESSION['session_id']);
                unset($_SESSION['session_name']);
                echo "<script>alert('이용해 주셔서 감사합니다')</script>";
                echo "<script>location.replace('./main.php')</script>";
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