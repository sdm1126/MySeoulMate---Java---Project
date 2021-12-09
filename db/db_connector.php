<?php
session_start();

// 1. 데이터베이스 시간 설정
date_default_timezone_set("Asia/Seoul");

// 2. 데이터베이스 접속 및 오류처리
// 2-1. 접속
$mysql_host = "localhost";
$mysql_id = "root";
$mysql_password = "123456";
$con = mysqli_connect($mysql_host, $mysql_id, $mysql_password);
// 2-2. 오류처리
if (!$con) {
    die("데이터베이스 연결 실패" . mysqli_connect_errno());
}

// 3. 데이터베이스가 이미 존재하는 지 여부 확인
$database_flag = false;
// 3-1. 쿼리문 생성
$sql = "SHOW DATABASES";
// 3-2. 쿼리문 전송 및 결과 받기
$result = mysqli_query($con, $sql) or die("데이터베이스 확인 실패" . mysqli_error($con));
// 3-3. 결과로부터 값 불러오기
while ($row = mysqli_fetch_array($result)) {
    // 'Database'열을 확인하여 'theMiraeSeoul'이 있을 경우, flag = true로 변경
    if ($row["Database"] == "themiraeseoul") {
        $database_flag = true;
        break;
    }
}

// 4. 데이터베이스가 존재하지 않을 경우 생성
if ($database_flag === false) {
    // 4-1. 쿼리문 생성
    $sql = "CREATE DATABASE theMiraeSeoul";
    // 4-2. 쿼리문 전송 및 결과 받기
    $value = mysqli_query($con, $sql) or die("데이터베이스 생성 실패" . mysqli_error($con));
    // 4-3. 결과 확인
    if ($value === true) {
        echo "<script>alert('theMiraeSeoul 데이터베이스가 생성되었습니다.')</script>";
    }
}

// 5. (쿼리문을 보낼 기본) 데이터베이스 선택 및 오류처리
// 5-1. 선택
$dbcon = mysqli_select_db($con, "theMiraeSeoul") or die("데이터베이스 선택 실패" . mysqli_error($con));
// 5-2. 오류처리
if (!$dbcon) {
    echo "<script>alert('theMiraeSeoul 데이터베이스 선택에 실패했습니다.')</script>";
}

// 6. 공용 함수 생성
// 6-1. 메세지 표시 후 뒤로가기 함수
function alert_back($message) {
    echo "<script>alert($message);</script>";
    echo "<script>history.go(-1);</script>";
    exit();
}

// 6-2. MySQL 인젝션 방어 함수
function sql_escape($con, $content) {
    return mysqli_real_escape_string($con, $content);
}

// 6-3. 게시판 페이지 설정 함수
// 한 페이지 행 수, 현재 페이지, 총 페이지, URL
function get_paging($write_pages, $current_page, $total_page, $url) { 
    // URL이 예를 들어, 'memo_login&page=123'이 있으면 'memo_login&page=' 으로 변경(공통 적용하기 위함)
    $url = preg_replace('/\&page=[0-9]*/', '', $url) . '&amp;page=';

    $str = '';
    // 1. 현재 페이지가 1페이지가 아니고, 2페이지 이상이라면 처음 가기를 등록한다.
    ($current_page > 1) ? ($str .= '<a href="' . $url . '1" class="arrow pprev">처음</a>' . PHP_EOL) : ''; // 'PHP_EOL'은 \n 이라는 뜻

    // 2. 시작페이지와 끝페이지를 보여준다.(끝페이지가 중요)
    // 현재 12면 시작11~끝20
    $start_page = (((int)(($current_page - 1) / $write_pages)) * $write_pages) + 1;
    $end_page = $start_page + $write_pages - 1;
    if ($end_page >= $total_page) $end_page = $total_page;

    // 3 (예) 10페이지 넘어가면 11페이지부터 '이전'이 생김, 이전 버튼 누르면 10페이지 보임)
    if ($start_page > 1) $str .= '<a href="' . $url . ($start_page - 1) . '" class="arrow prev">이전</a>' . PHP_EOL;

    // 4. 전체 페이지가 2페이지 이상이면, 예) 현재 12페이지(=시작 11페이지, 끝 20페이지)
    // [처음][이전][11][12][13]...[19][20]
    if ($total_page > 1) {
        for ($k = $start_page; $k <= $end_page; $k++) {
            if ($current_page != $k)
                $str .= '<a href="' . $url . $k . '" class="">' . $k . '</a>' . PHP_EOL;
            else
                $str .= '<a href="' . $url . $k . '" class="active">' . $k . '</a>' . PHP_EOL;
        }
    }

    // 5. 전체 페이지가 끝 페이지보다 클 때 다음 누르면 다음이 있으니까 다음 페이지로 이동(예) 시작11~끝20일때, 20에서 다음 누르면 21로 감)
    if ($total_page > $end_page) $str .= '<a href="' . $url . ($end_page + 1) . '" class="arrow next">다음</a>' . PHP_EOL;

    // 6. 현재페이지가 전체 페이지보다 작다면 [처음][이전][11]스트롱[12]스트롱[13]...[19][20][다음][끝]
    if ($current_page < $total_page) {
        $str .= '<a href="' . $url . $total_page . '" class="arrow nnext">맨끝</a>' . PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}