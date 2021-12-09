<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.


$first_name                     = trim($_POST['first_name']);
$last_name                     = trim($_POST['last_name']);
$id                     = trim($_POST['id']);
$email1                = trim($_POST['email1']); // 이메일
$email2                = trim($_POST['email2']); // 이메일
$mobile1                    = $_POST['mobile1']; // 직업
$mobile2                    = $_POST['mobile2']; // 직업
$mobile3                    = $_POST['mobile3']; // 직업
if (!$id) {
    echo "<script>alert('아이디이 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}
if (!$first_name) {
    echo "<script>alert('이름이 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}

if (!$last_name) {
    echo "<script>alert('이름이 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}

if (!$email1) {
    echo "<script>alert('이메일이 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}
if (!$email2) {
    echo "<script>alert('이메일이 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}
if (!$mobile1) {
    echo "<script>alert('휴대전화 번호가 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}
if (!$mobile2) {
    echo "<script>alert('휴대전화 번호가 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}
if (!$mobile3) {
    echo "<script>alert('휴대전화 번호가 넘어오지 않았습니다.');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
    exit;
}

//이름 등의 정보로 비밀번호를 찾아오기
$sql = " SELECT * FROM user WHERE first_name = '$first_name' AND last_name='{$last_name}' AND mobile2 ='{$mobile2}' AND mobile1 ='{$mobile1}'AND mobile3 ='{$mobile3}'
AND email1='{$email1}'AND email2='{$email2}'AND email2='{$email2}' and id = '$id'"; // 회원가입을 시도하는 아이디가 사용중인 아이디인지 체크
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
//비밀번호를 복호화해서 가져오기
$sql = "SELECT UNHEX('" . $row['password'] . "') as password;";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo "<script>alert('password는 {$row['password']} 입니다.');</script>";
    echo "<script>location.replace('./login.php');</script>";
    exit;
} else {
    echo "<script>alert('그런 사용자는 없습니다..');</script>";
    echo "<script>location.replace('./find_password.php');</script>";
}
