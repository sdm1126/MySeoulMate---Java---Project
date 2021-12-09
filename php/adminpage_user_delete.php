<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.
$id = $_POST['id'];

$sql = " delete from user where id = '{$id}' "; // 넘어온 post값으로 id검색
$result = mysqli_query($con, $sql);

if ($result) {
    echo "<script>alert('탈퇴 되었습니다.');</script>";
    echo "<script>location.replace('./adminpage_user.php?page=1');</script>";
}