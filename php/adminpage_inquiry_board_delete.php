<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.
$no = $_POST['no'];

// 파일 이름을 구한다
$sql = "SELECT attached_file FROM inquiry WHERE no = $no";
$result = mysqli_query($con, $sql) or die('fail' . mysqli_error($con));
$row = mysqli_fetch_assoc($result);
// 파일 경로로 삭제
$delete_file = $row['attached_file'];
if($delete_file !== ""){
    unlink("../data/".$delete_file);
}

$sql = " delete from inquiry where no = '{$no}' "; // 넘어온 post값으로 no검색
$result = mysqli_query($con, $sql);


if ($result) {
    echo "<script>alert('삭제 되었습니다.');</script>";
    echo "<script>location.replace('./adminpage_inquiry_board.php?page=1');</script>";
}
