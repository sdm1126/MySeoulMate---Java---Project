<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.


$first_name 		= trim($_POST['first_name']); //이름
$last_name 			= trim($_POST['second_name']);
$full_name          = $first_name . $last_name;
$id 				= trim($_POST['id']);
$password			= trim($_POST['password']); // 첫번째 입력 패스워드
$password_re		= trim($_POST['password_re']); // 두번째 입력 패스워드
$email1				= trim($_POST['email1']); // 이메일
$email2				= trim($_POST['email2']); // 이메일
$gender				= $_POST['gender']; // 성별
$mobile1			= $_POST['mobile1']; // 휴대전화
$mobile2			= $_POST['mobile2']; // 휴대전화
$mobile3			= $_POST['mobile3']; // 휴대전화
$datetime			= date('Y-m-d H:i:s', time()); // 가입일
if (!$id) {
	echo "<script>alert('아이디가 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

if (!$password) {
	echo "<script>alert('비밀번호가 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

if ($password != $password_re) {
	echo "<script>alert('비밀번호가 일치하지 않습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

if (!$first_name) {
	echo "<script>alert('이름이 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

if (!$last_name) {
	echo "<script>alert('이름이 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

if (!$email1) {
	echo "<script>alert('이메일이 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}
if (!$email2) {
	echo "<script>alert('이메일이 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}
if (!$mobile1) {
	echo "<script>alert('전화번호가 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}
if (!$mobile2) {
	echo "<script>alert('전화번호가 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}
if (!$mobile3) {
	echo "<script>alert('전화번호가 넘어오지 않았습니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}

$sql = " SELECT HEX('$password') AS pass "; // 입력한 비밀번호를 MySQL password() 함수를 이용해 암호화해서 가져옴
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$password = $row['pass'];


$sql = " SELECT * FROM user WHERE id = '$id' "; // 회원가입을 시도하는 아이디가 사용중인 아이디인지 체크
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) { // 만약 사용중인 아이디라면 알림창을 띄우고 회원가입 페이지로 이동
	echo "<script>alert('이미 사용중인 회원아이디 입니다.');</script>";
	echo "<script>location.replace('./registration.php');</script>";
	exit;
}
//
$sql = " INSERT INTO user
				SET no = null,
					 last_name = '$last_name',
					 first_name = '$first_name',
					 full_name = '$full_name',
					 gender = '$gender',
					 id = '$id',
					 password = '$password',
					 email1 = '$email1',
					 email2 = '$email2',
					 mobile1 = '$mobile1',
					 mobile2 = '$mobile2',
					 mobile3 = '$mobile3',
					 registered_date = '$datetime'";
$result = mysqli_query($con, $sql);

if ($result) {
	echo "<script>alert('회원가입 되었습니다.');</script>";
	echo "<script>location.replace('./login.php');</script>";
	exit;
}