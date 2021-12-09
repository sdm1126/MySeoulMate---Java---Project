<?php
    include_once('../db/db_connector.php');

// 엑셀로 파일을 받기 위한 코드
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
//filename = 저장되는 파일명을 설정합니다.
header( "Content-Disposition: attachment; filename = excel_test.xls" );     
header( "Content-Description: PHP4 Generated Data" );

// 가져올 테이블 이름을 구함
$tableName = $_GET['mode'];
$search = isset($_GET['search']) ? ($_GET['search']) : "";
$nameId = isset($_GET['nameId']) ? ($_GET['nameId']) : "";

//엑셀 파일로 만들고자 하는 데이터의 테이블을 만듭니다
$column = array();
// 테이블 필드명을 구하는 sql문
$sql = "DESC $tableName";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)){
    $column[] = $row['Field'];
}

// 엑셀파일 1행에 테이블 컬럼명 출력하기
$EXCEL_FILE = "
<table border='1'>
    <tr>";
    for ($i=0; $i <count($column) ; $i++) { 
        $EXCEL_FILE .= "<td>$column[$i]</td>"; 
    }
    $EXCEL_FILE .= "</tr>";

    // 테이블 값 출력
    $sql = "SELECT * FROM {$tableName} ";
    if(isset($_GET['search']) && isset($_GET['nameId'])){
        $sql .= " WHERE $search LIKE '%{$nameId}%' ";
    }
    $result = mysqli_query($con, $sql);

// DB 에 저장된 데이터를 테이블 형태로 저장합니다.

while ($row = mysqli_fetch_assoc($result)) {
$EXCEL_FILE .= "
    <tr>";
    for ($i=0; $i <count($column) ; $i++) { 
        $EXCEL_FILE .= "<td>".$row[$column[$i]]."</td>"; 
       
    }      
    $EXCEL_FILE .= "</tr>";
}
$EXCEL_FILE .= "</table>";

// 만든 테이블을 출력해줘야 만들어진 엑셀파일에 데이터가 나타납니다.
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo $EXCEL_FILE;
?>