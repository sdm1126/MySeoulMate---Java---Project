<?php
include("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.
$search;
$nameId;
//1.서치값에 따라 페이지수 결정
if (!isset($_GET['search'])) {
    $sql = " SELECT COUNT(*) AS `cnt` FROM reservation"; // member 테이블에 등록되어있는 회원의 수를 구함
} else {
    if ($_GET['search'] === 'name') {
        $nameId = $_GET['nameId'];
        $search = $_GET['search'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM reservation where full_name like '%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
    } else if ($_GET['search'] === 'no') {
        $nameId = $_GET['nameId'];
        $search = $_GET['search'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM reservation where no like '%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
    } else {
        $nameId = $_GET['nameId'];
        $search = $_GET['search'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM reservation where check_in like '%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
    }
}

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$total_count = $row['cnt'];
// 페이지당 목록 수
$page_rows = 5;
//보여줘야할 페이지
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
// 전체 페이지 계산
$total_page  = ceil($total_count / $page_rows);

if ($page < 1) {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$list = array(); // 회원 정보를 담을 배열 선언
//해당되는 페이지 레코드를 가져온다.    
if (!isset($_GET['search'])) {
    $sql = " SELECT * FROM reservation ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
} else {
    if ($_GET['search'] === 'name') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM reservation where full_name like '%{$nameId}%' ORDER BY full_name desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    } else if ($_GET['search'] === 'no') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM reservation where no like'%{$nameId}%'"; // 회원 정보를 조회
    } else {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM reservation where check_in like'%{$nameId}%' ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    }
}

$result = mysqli_query($con, $sql);
for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
    $list[$i] = $row;
    $list_num = $total_count - ($page - 1) * $page_rows; // 회원 순번
    $list[$i]['num'] = $list_num - $i;
}
//페이징을 시작한다. 
$str = ''; // 페이징 시작
//7.1 1페이지 아닌 2페이지 이라면
if ($page > 1) {

    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_reservation.php?page=1" class="arrow pprev"><<</a>';
    } else {
        if ($_GET['search'] === 'name') {
            $str .= '<a href="./adminpage_reservation.php?page=1&search=name&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        } else if ($_GET['search'] === 'no') {
            $str .= '<a href="./adminpage_reservation.php?page=1&search=no&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        } else {
            $str .= '<a href="./adminpage_reservation.php?page=1&search=check_in&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        }
    }
}
//7.2 시작페이지를 등록한다. 끝페이지를 구한다.
//끝페이지가 중요함(56페이지일때 시작페이지 51~60페이지)
//끝페이지가 총페이지 보다 크면 총페이지가 끝페이지가 된다.51~56페이지가 됨
$start_page = (((int)(($page - 1) / $page_rows)) * $page_rows) + 1;
$end_page = $start_page + $page_rows - 1;

if ($end_page >= $total_page) $end_page = $total_page;
// 7.3시작페이지가 2페이지 이상이라면 이전페이지를 만들어 준다.
//[처음][이전][11]스트롱[12][13]...[19][20]=>//[처음][이전][1][2][3]...[9][10]스트롱
if ($start_page > 1) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_reservation.php?page=' . ($start_page - 1) . '" class="arrow prev"><</a>';
    } else {

        if ($_GET['search'] === 'name') {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($start_page - 1) . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'no') {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($start_page - 1) . '&search=no&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($start_page - 1) . '&search=check_in&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//7.4 전체페이지가 2이상이면 시작페이지가  11페이지부터 끝이 20이면
//[처음][이전][11]스트롱[12][13]...[19][20]
if ($total_page > 1) {

    if (!isset($_GET['search'])) {
        for ($k = $start_page; $k <= $end_page; $k++) {
            if ($page != $k)
                $str .= '<a href="./adminpage_reservation.php?page=' . $k . '" class="pg_page">' . $k . '</a>';
            else
                $str .= '<a class="active">' . $k . '</a>';
        }
    } else {
        if ($_GET['search'] === 'name') {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_reservation.php?page=' . $k . '&search=name&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        } else if ($_GET['search'] === 'no') {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_reservation.php?page=' . $k . '&search=no&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        } else {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_reservation.php?page=' . $k . '&search=check_in&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        }
    }
}
// 7.5전체페이지가 토탈페이지보다 많다면    56>20이라면
//[처음][이전][11]스트롱[12][13]...[19][20]=>//[처음][이전][21]스트롱[22][23]...[29][30]
if ($total_page > $end_page) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_reservation.php?page=' . ($end_page + 1) . '" class="arrow next">></a>';
    } else {
        if ($_GET['search'] === 'name') {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($end_page + 1) . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'no') {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($end_page + 1) . '&search=no&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_reservation.php?page=' . ($end_page + 1) . '&search=check_in&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//현재페이지가 전체페이지보다 작다면 //[처음][이전][11]스트롱[12][13]...[19][20]
if ($page < $total_page) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_reservation.php?page=' . $total_page . '" class="arrow nnext">>></a>';
    } else {
        if ($_GET['search'] === 'name') {
            $str .= '<a href="./adminpage_reservation.php?page=' . $total_page . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'no') {
            $str .= '<a href="./adminpage_reservation.php?page=' . $total_page . '&search=no&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_reservation.php?page=' . $total_page . '&search=check_in&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
// 
if ($str) // 페이지가 있다면 생성
    $write_page = "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
else
    $write_page = "";

mysqli_close($con); // 데이터베이스 접속 종료
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/aside.css">
  <link rel="stylesheet" href="../css/adminpage_reservation.css">
  <link rel="stylesheet" href="../css/page.css">
  <script src="../js/adminpage_reservation.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
  <div class="container">
    <!-- header -->
    <?php
        include('./header.php');
        ?>

    <!-- aside -->
    <aside>
      <div>
        <ul>
          <li class="title">관리자 페이지
          </li>
          <hr>
          <li><a href="./adminpage_user.php?page=1">전체 정보</a></li>
          <li><a href="./adminpage_reservation.php?page=1"><b>전체 예약</b></a></li>
          <li><a href="./adminpage_inquiry_board.php?page=1">전체 문의</a></li>
        </ul>
      </div>
    </aside>
    <main>
      <div class="h2">
        <h2 id="h2">전 체 예 약</h2>
      </div>
      <hr>
      <div class="search">
        <select class="custom-select" id="root">
          <option value="name">성명</option>
          <option value="no">예약번호</option>
          <option value="check_in">체크인</option>
        </select>
        <input type="text" class="custum-search form-control" id="nameId">
        <button type="button" class="btn btn-secondary" id="search">조회</button>
        <button type="button" class="btn btn-excel" id="search_excel"><img src="../image/excel_icon.png">로그
          출력</button>
      </div>
      <div class="table">
        <table>
          <th>성명</th>
          <th>예약번호</th>
          <th>체크인</th>
          <th>체크아웃</th>
          <th>취소</th>
          <?php
                    for ($i = 0; $i < count($list); $i++) {
                    ?>
          <form action="adminpage_reservation_delete.php" method="post">
            <input type="hidden" name="no" value="<?php echo $list[$i]['no'] ?>">
            <tr>
              <td><?php echo $list[$i]['full_name'] ?></td>
              <td><?php echo $list[$i]['no'] ?></td>
              <td><?php echo $list[$i]['check_in'] ?></td>
              <td><?php echo $list[$i]['check_out'] ?></td>
              <td><button type="submit" class="btn btn-secondary btn1">취소</button></td>
            </tr>
          </form>
          <?php } ?>
          <?php if (count($list) == 0) {
                        echo '<tr><td colspan="9">등록된 예약이 없습니다.</td></tr>';
                    } ?>
        </table>
        <div class="page_wrap">
          <div class="page_nation">
            <p><?php echo $write_page;  ?>
              <!-- 페이지 -->
            </p>
          </div>
        </div>
      </div>
    </main>


    <!-- footer -->
    <?php
        include('./footer.php');
        ?>
  </div>
  <script>
  $("#root").val('<?= $search ?>').prop("selected", true);
  </script>
</body>

</html>