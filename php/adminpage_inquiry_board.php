<?php
include_once("../db/db_connector.php");  // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.
$nameId;
$search;
//1.서치값에 따라 페이지수 결정
if (!isset($_GET['search'])) {
    $sql = " SELECT COUNT(*) AS `cnt` FROM inquiry"; // member 테이블에 등록되어있는 회원의 수를 구함
} else {
    if ($_GET['search'] === 'id') {
        $search = $_GET['search'];
        $nameId = $_GET['nameId'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM inquiry where id like '%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
    } else if ($_GET['search'] === 'title') {
        $search = $_GET['search'];
        $nameId = $_GET['nameId'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM inquiry where title like '%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
    } else {
        $search = $_GET['search'];
        $nameId = $_GET['nameId'];
        $sql = " SELECT COUNT(*) AS `cnt` FROM inquiry where written_date like'%" . $nameId . "%'"; // member 테이블에 등록되어있는 회원의 수를 구함
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
    $sql = " SELECT * FROM inquiry ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
} else {
    if ($_GET['search'] === 'id') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM inquiry where id like '%{$nameId}%' ORDER BY written_date desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    } else if ($_GET['search'] === 'title') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM inquiry where title like '%{$nameId}%' ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    } else {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM inquiry where written_date like '%{$nameId}%' ORDER BY written_date desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
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
//1페이지 아닌 2페이지 이라면
if ($page > 1) {

    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_inquiry_board.php?page=1" class="arrow pprev"><<</a>';
    } else {
        if ($_GET['search'] === 'id') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=1&search=id&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        } else if ($_GET['search'] === 'title') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=1&search=title&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        } else {
            $str .= '<a href="./adminpage_inquiry_board.php?page=1&search=written_date&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        }
    }
}
//2 시작페이지를 등록한다. 끝페이지를 구한다.
//끝페이지가 중요함(56페이지일때 시작페이지 51~60페이지)
//끝페이지가 총페이지 보다 크면 총페이지가 끝페이지가 된다.51~56페이지가 됨
$start_page = (((int)(($page - 1) / $page_rows)) * $page_rows) + 1;
$end_page = $start_page + $page_rows - 1;

if ($end_page >= $total_page) $end_page = $total_page;
// 3시작페이지가 2페이지 이상이라면 이전페이지를 만들어 준다.
//[처음][이전][11]스트롱[12][13]...[19][20]=>//[처음][이전][1][2][3]...[9][10]스트롱
if ($start_page > 1) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($start_page - 1) . '" class="arrow prev"><</a>';
    } else {

        if ($_GET['search'] === 'id') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($start_page - 1) . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'title') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($start_page - 1) . '&search=title&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($start_page - 1) . '&search=written_date&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//4 전체페이지가 2이상이면 시작페이지가  11페이지부터 끝이 20이면
//[처음][이전][11]스트롱[12][13]...[19][20]
if ($total_page > 1) {

    if (!isset($_GET['search'])) {
        for ($k = $start_page; $k <= $end_page; $k++) {
            if ($page != $k)
                $str .= '<a href="./adminpage_inquiry_board.php?page=' . $k . '" class="pg_page">' . $k . '</a>';
            else
                $str .= '<a class="active">' . $k . '</a>';
        }
    } else {
        if ($_GET['search'] === 'id') {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_inquiry_board.php?page=' . $k . '&search=id&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        } else if ($_GET['search'] === 'title') {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_inquiry_board.php?page=' . $k . '&search=title&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        } else {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./adminpage_inquiry_board.php?page=' . $k . '&search=written_date&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        }
    }
}
// 5전체페이지가 토탈페이지보다 많다면    56>20이라면
//[처음][이전][11]스트롱[12][13]...[19][20]=>//[처음][이전][21]스트롱[22][23]...[29][30]
if ($total_page > $end_page) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($end_page + 1) . '" class="arrow next">></a>';
    } else {
        if ($_GET['search'] === 'id') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($end_page + 1) . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'title') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($end_page + 1) . '&search=title&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . ($end_page + 1) . '&search=written_date&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//현재페이지가 전체페이지보다 작다면 //[처음][이전][11]스트롱[12][13]...[19][20]
if ($page < $total_page) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./adminpage_inquiry_board.php?page=' . $total_page . '" class="arrow nnext">>></a>';
    } else {
        if ($_GET['search'] === 'id') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . $total_page . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else if ($_GET['search'] === 'title') {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . $total_page . '&search=title&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./adminpage_inquiry_board.php?page=' . $total_page . '&search=written_date&nameId=' . $nameId . '" class="arrow prev"><</a>';
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
  <link rel="stylesheet" href="../css/adminpage_inquiry_board.css">
  <link rel="stylesheet" href="../css/page.css">
  <script src="../js/adminpage_inquiry_board.js"></script>
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
          <li><a href="./adminpage_reservation.php?page=1">전체 예약</a></li>
          <li><a href="./adminpage_inquiry_board.php?page=1"><b>전체 문의</a></b></li>
        </ul>
      </div>
    </aside>
    <main>
      <article class="h2">
        <h2 id="h2">전 체 문 의</h2>
      </article>
      <hr>
      <article class="search">
        <select class="custom-select" id="root" name="option">
          <option value="id">아이디</option>
          <option value="title">제목</option>
        </select>
        <input type="text" class="custum-search form-control" id="nameId">
        <button type="button" class="btn btn-secondary" id="search">조회</button>
        <button type="button" class="btn btn-excel" id="search_excel"><img src="../image/excel_icon.png">로그 출력</button>
      </article>
      <article class="table">
        <table>
          <th>번호</th>
          <th>아이디</th>
          <th>제목</th>
          <th>작성일자</th>
          <th>삭제</th>
          <?php
                    for ($i = 0; $i < count($list); $i++) {
                    ?>
          <form action="adminpage_inquiry_board_delete.php" method="post">
            <input type="hidden" name="no" value="<?php echo $list[$i]['no'] ?>">
            <tr>
              <td><?php echo $list[$i]['no'] ?></td>
              <td><?php echo $list[$i]['id'] ?></td>
              <td><a
                  href="adminpage_inquiry_read.php?no=<?php echo $list[$i]['no'] ?>&id=<?php echo $list[$i]['id'] ?>"><?php echo $list[$i]['title'] ?></a>
              </td>
              <td><?php echo $list[$i]['written_date'] ?></td>
              <td><button type="submit" class="btn btn-secondary btn1">삭제</button></td>
            </tr>
          </form>
          <?php } ?>
          <?php if (count($list) == 0) {
                        echo '<tr><td colspan="9">등록된 문의가 없습니다.</td></tr>';
                    } ?>
        </table>
        <br>
        <div class="page_wrap">
          <div class="page_nation">
            <p><?php echo $write_page; ?>
              <!-- 페이지 -->
            </p>
          </div>
        </div>
      </article>
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