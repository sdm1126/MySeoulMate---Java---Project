<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.

$id			= trim($_POST['id']);
$password	= trim($_POST['password']);

if (!$id || !$password) {
	echo "<script>alert('회원아이디나 비밀번호가 공백이면 안됩니다.');</script>";
	echo "<script>location.replace('./login.php');</script>";
	exit;
}

$sql = " SELECT * FROM user WHERE id = '$id' "; // 회원 테이블에서 해당 아이디가 존재하는지 체크
$result = mysqli_query($con, $sql);
$mb = mysqli_fetch_assoc($result);;

$sql = "SELECT UNHEX('" . $mb['password'] . "') as password;";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
if (!$mb['id'] || !($password === $row['password'])) { // 존재하는 아이디인지, 입력한 패스워드가 회원 테이블에 저장된 패스워드와 동일한지 체크
	echo "<script>alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');</script>";
	echo "<script>location.replace('./login.php');</script>";
	exit;
}

$_SESSION['session_id'] = $id; // 아이디/비밀번호 확인 후 세션 생성
$_SESSION['session_name'] = $mb['full_name'];


mysqli_close($con); // 데이터베이스 접속 종료

if (isset($_SESSION['session_id'])) { // 세션이 있다면 로그인 확인 페이지로 이동
	echo "<script>alert('로그인 되었습니다.');</script>";
	echo "<script>location.replace('./main.php');</script>";
}