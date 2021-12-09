<?php
  include_once $_SERVER['DOCUMENT_ROOT']."/TheMiraeSeoul/db/db_connector.php";
  
  error_reporting(E_ALL & ~E_WARNING);
  $nameId;

  $search = (isset($_GET['search'])) ? $_GET['search'] : "title";
  if (!isset($_GET['search'])) {
      $sql = " SELECT COUNT(*) AS `cnt` FROM notice"; // member 테이블에 등록되어있는 회원의 수를 구함
  } else {
      // 제목 검색
      if ($search === 'title') {
          $nameId = $_GET['nameId'];
          $sql = " SELECT COUNT(*) AS `cnt` FROM notice where title ='" . $nameId . "'"; // member 테이블에 등록되어있는 회원의 수를 구함
      // 내용 검색
      } else {
          $nameId = $_GET['nameId'];
          $sql = " SELECT COUNT(*) AS `cnt` FROM notice where content ='" . $nameId . "'"; // member 테이블에 등록되어있는 회원의 수를 구함
      }
  }
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_assoc($result);
  
  // 총 목록 수
  $total_count = $row['cnt'];
  // 페이지당 목록 수
  $page_rows = 5;
  //보여줘야할 페이지
  $page = isset($_GET["page"]) ? $_GET["page"] : 1;
  // 전체 페이지 계산
  $total_page  = ceil($total_count / $page_rows);
  // 페이지가 없으면 첫 페이지 (1 페이지)
  if ($page < 1) {
    $page = 1;
  }
  // 시작 열을 구함 
  $from_record = ($page - 1) * $page_rows; 
  // 회원 정보를 담을 배열 선언
  $list = array(); 
  //해당되는 페이지 레코드를 가져온다.
  if (!isset($_GET['search'])) {
    $sql = " SELECT * FROM notice ORDER BY no desc LIMIT {$from_record}, {$page_rows}"; // 회원 정보를 조회
  } else {
    if ($search === 'title') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM notice where title like '%{$nameId}%' ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    } else if ($search === 'content') {
        $nameId = $_GET['nameId'];
        $sql = " SELECT * FROM notice where content like '%{$nameId}%' ORDER BY no desc LIMIT {$from_record}, {$page_rows} "; // 회원 정보를 조회
    }
  }

  $result = mysqli_query($con, $sql);
  for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
      $list[$i] = $row;
      $list_num = $total_count - ($page - 1) * $page_rows; // 회원 순번
      $list[$i]['num'] = $list_num - $i;
  }
  // 페이징을 시작한다. 
  $str = '';
  //7.1 1페이지 아닌 2페이지 이라면
  if ($page > 1) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./service_notice_board.php?page=1" class="arrow pprev"><<</a>';
    } else {
        if ($_GET['search'] === 'title') {
            $str .= '<a href="./service_notice_board.php?page=1&search=name&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
        } else {
            $str .= '<a href="./service_notice_board.php?page=1&search=id&nameId=' . $nameId . '" class="arrow pprev"><<</a>';
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
        $str .= '<a href="./service_notice_board.php?page=' . ($start_page - 1) . '" class="arrow prev"><</a>';
    } else {
        if ($_GET['search'] === 'title') {
            $str .= '<a href="./service_notice_board.php?page=' . ($start_page - 1) . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./service_notice_board.php?page=' . ($start_page - 1) . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//7.4 전체페이지가 2이상이면 시작페이지가  11페이지부터 끝이 20이면
//[처음][이전][11]스트롱[12][13]...[19][20]
if ($total_page > 1) {

    if (!isset($_GET['search'])) {
        for ($k = $start_page; $k <= $end_page; $k++) {
            if ($page != $k)
                $str .= '<a href="./service_notice_board.php?page=' . $k . '" class="pg_page">' . $k . '</a>';
            else
                $str .= '<a class="active">' . $k . '</a>';
        }
    } else {
        if ($_GET['search'] === 'title') {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./service_notice_board.php?page=' . $k . '&search=name&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
                else
                    $str .= '<a class="active">' . $k . '</a>';
            }
        } else {
            for ($k = $start_page; $k <= $end_page; $k++) {
                if ($page != $k)
                    $str .= '<a href="./service_notice_board.php?page=' . $k . '&search=id&nameId=' . $nameId . '" class="pg_page">' . $k . '</a>';
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
        $str .= '<a href="./service_notice_board.php?page=' . ($end_page + 1) . '" class="arrow next">></a>';
    } else {
        if ($_GET['search'] === 'title') {
            $str .= '<a href="./service_notice_board.php?page=' . ($end_page + 1) . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./service_notice_board.php?page=' . ($end_page + 1) . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
//현재페이지가 전체페이지보다 작다면 //[처음][이전][11]스트롱[12][13]...[19][20]
if ($page < $total_page) {
    if (!isset($_GET['search'])) {
        $str .= '<a href="./service_notice_board.php?page=' . $total_page . '" class="arrow nnext">>></a>';
    } else {
        if ($_GET['search'] === 'title') {
            $str .= '<a href="./service_notice_board.php?page=' . $total_page . '&search=name&nameId=' . $nameId . '" class="arrow prev"><</a>';
        } else {
            $str .= '<a href="./service_notice_board.php?page=' . $total_page . '&search=id&nameId=' . $nameId . '" class="arrow prev"><</a>';
        }
    }
}
// 
if ($str) // 페이지가 있다면 생성
    $write_page = "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
else
    $write_page = "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/service_notice_board.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/aside.css">
  <script src="../js/service_notice_board.js "></script>
  <link rel="stylesheet" href="../css/page.css">
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
          <li class="title">고객 서비스</li>
          <hr>
          <li><b><a href="./service_notice_board.php">공지사항</a></b></li>
          <li><a href="./service_faq.php">F A Q</a></li>
          <li><a href="./service_inquiry.php">문의하기</a></li>
        </ul>
      </div>
    </aside>

    <!-- main -->
    <main>
      <!-- main1 -->
      <div class="h2">
        <h2>공 지 사 항</h2>
      </div>
      <hr>

      <!-- main2 -->
      <div class="search">
        <select name="" id="root">
          <option value="title" <?= ($search === "title" ? "selected" : "") ?>>제목</option>
          <option value="content" <?= ($search === "content" ? "selected" : "") ?>>내용</option>
        </select>
        <input type="text" id="nameId">
        <input type="submit" value="검 색" id="search">
      </div>
      <?php 
      $result = mysqli_query($con, $sql);
      for($i=0;   $row = mysqli_fetch_array($result); $i++){
        $list[$i] = $row;
        $written_date[$i]  = $list[$i]['written_date'];
      }
      ?>

      <!-- main3 -->
      <div class="table">
        <table>
          <th>번 호</th>
          <th>제 목</th>
          <th>성 명</th>
          <th>작 성 일 자</th>
          <th>조 회 수</th>

          <?php
          if(isset($list)) {
            if(count($list) === 0) {
              echo "<tr>";
              echo "<td colspan='5'>등록된 내용이 존재하지 않습니다.</td>";
              echo "</tr>";
            }
            for($i=0; $i< count($list); $i++) { 
                echo "<tr>";
                $no = $list[$i]['no'];  
                echo "<td>{$list[$i]['no'] }</td>";
                echo "<td><a href='../php/service_notice_read.php?mode=글읽기&title=".$list[$i]['title']."&no=$no'>{$list[$i]['title']}</a></td>";
                echo "<td>관리자</td>";
                echo "<td>{$written_date[$i]}</td>";
                echo "<td>{$list[$i]['read_count']}</td>";
                echo "</tr>";
              }
          }
          ?>
        </table>
      </div>

      <!-- main4 -->
      <article class="button">
        <div class="paging">
          <div class="page_wrap">
            <div class="page_nation">
              <p><?php echo $write_page; ?></p>
            </div>
          </div>
        </div>
        <div class="write">
          <?php
          if(isset($_SESSION['session_id'])&& $_SESSION['session_id'] === 'admin'){ ?>
          <a href="../php/service_notice_read.php?mode=글쓰기"><input type="button" value="글쓰기"></a>
          <?php } ?>
        </div>
      </article>

    </main>

    <!-- footer -->
    <?php
      include('./footer.php');      
    ?>

</body>

</html>