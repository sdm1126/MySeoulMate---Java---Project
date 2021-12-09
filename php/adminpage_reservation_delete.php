<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.
$no = $_POST['no'];

$sql = "CALL `reservation_delete_procedure`($no)"; //  넘어온 post값으로 no검색
$result = mysqli_query($con, $sql);

if ($result) {
    echo "<script>alert('취소 되었습니다.');</script>";
    echo "<script>location.replace('./adminpage_reservation.php?page=1');</script>";
}