<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";

    // 1. 로그인 관련
    if(isset($_SESSION['session_id'])) {
        $session_id = $_SESSION['session_id'];
    } else {
        echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
        echo "<script>location.replace('./login.php');</script>";
        mysqli_close($con);
        exit(); 
    }

    // 2. 검색 관련(select: 검색 기준, keyword: 검색어)
    $select = isset($_GET['select']) ? $_GET['select'] : "no";
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";

    // 2-1. 검색어 미입력 시
    if(trim($keyword) === "") {
        $sql = "SELECT * FROM reservation ORDER BY no DESC";
    
    // 2-2. 검색어 입력 시
    } else {
        // 1. 예약번호 검색
        if($select === 'no') {
            $keyword = $_GET['keyword'];
            $sql = "SELECT * FROM reservation WHERE no = {$keyword} ORDER BY no DESC" ;
            
        // 2. 상품명 검색
        } else if($select === 'deal_name') {
            $keyword = $_GET['keyword'];
            $sql = "SELECT * FROM reservation WHERE deal_name LIKE '%{$keyword}%' ORDER BY no DESC";
            
        // 3. 체크인 검색
        } else {
            $keyword = $_GET['keyword'];
            $sql = "SELECT * FROM reservation WHERE check_in = '{$keyword}' ORDER BY no DESC";
        } 
    }

    $result = mysqli_query($con, $sql);
                
    $list = array();
    for($i = 0; $row = mysqli_fetch_array($result); $i++) {
        $list[$i] = $row;
    }

    // 3. 페이징 관련
    // 3-1. 전체 항목 수
    $total_count = count($list);
    
    // 3-2. 한 페이지 당 항목 수
    $page_rows = 5;

    // 3-3. 현재 페이지
    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

    // 3-4. 전체 페이지 수
    $total_page = ceil($total_count / $page_rows);

    // 3-5. 페이지 당 첫번째 항목 인덱스
    $from_record = ($page - 1) * $page_rows;

    // 3-6. 빈 배열 생성
    $list = array();

    // 3-7. 페이지 별 항목을 가져온다.
    $sql = "SELECT * FROM reservation ORDER BY reservation_date DESC LIMIT {$from_record}, {$page_rows}";
    $result = mysqli_query($con, $sql);
    for($i = 0; $row = mysqli_fetch_array($result); $i++) {
        $list[$i] = $row;
        $list_num = $total_count - ($page - 1) * $page_rows;
        $list[$i]['num'] = $list_num - $i; // 'num'열: 항목 번호(글 삭제 또는 추가 시 변동)
    }

    // 3-8. 시작 페이지, 끝 페이지 지정
    $start_page = ((int)(($page - 1) / $page_rows) * $page_rows) + 1;
    $end_page = $start_page + $page_rows - 1;

    // 3-9. url 지정
    $url = "./mypage_reservation.php?page=";

    // 3-10. 페이지 구현 HTML문 생성
    $write_page = get_paging($page_rows, $page, $total_page, $url);

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../js/mypage_reservation.js"></script>
    <link rel="stylesheet" href="../css/mypage_reservation.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/aside.css">
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
                    <li class="title">마이 페이지</li>
                    <hr>
                    <li><a href="./mypage_user.php">내 정보</a></li>
                    <li><a href="./mypage_reservation.php?page=1&option=no"><b>내 예약</b></a></li>
                    <li><a href="./mypage_inquiry_board.php?option=title&page=1">내 문의</a></li>
                    <li><a href="./mypage_resignation.php">회원탈퇴</a></li>
                </ul>
            </div>
        </aside>

        <!-- main -->
        <main>
            <!-- main1 -->
            <article class="h2">
                <h2>내 예약</h2>
                <div class="span">
                    <span>※ 변경 / 취소는 객실예약실로 문의 바랍니다.</span>
                </div>
            </article>
            <hr>

            <!-- main2 -->
            <form action="./mypage_reservation.php" method="get">
                <article class="search">
                    <select name="select" id="select">
                        <option value="no" <?php echo ($select === "no" ? "selected" : "") ?>>예약번호</option>
                        <option value="deal_name" <?php echo ($select === "deal_name" ? "selected" : "") ?>>상품명</option>
                        <option value="check_in" <?php echo ($select === "check_in" ? "selected" : "") ?>>체크인</option>
                    </select>
                    <input type="text" name="keyword" id="keyword">
                    <button>조 회</button>
                </article>
            </form>

            <!-- main3 -->
            <article class="table">
                <table>
                    <th id="no">예약번호</th>
                    <th id="deal_name">상품명</th>
                    <th id="room_type">객실타입</th>
                    <th id="check_in">체크인</th>
                    <th id="check_out">체크아웃</th>
                    <th id="reservation_date">예약일자</th>
                    <?php for($i = 0; $i < count($list); $i++) { ?>
                    <tr>
                        <td><?php echo $list[$i]['no'] ?></td>
                        <td><?php echo $list[$i]['deal_name'] ?></td>
                        <td><?php echo $list[$i]['room_type'] ?></td>
                        <td><?php echo $list[$i]['check_in'] ?></td>
                        <td><?php echo $list[$i]['check_out'] ?></td>
                        <td><?php echo $list[$i]['reservation_date'] ?></td>
                    </tr>
                    <?php }
                    if(count($list) === 0) {    
                        echo '<tr><td colspan="6">해당되는 예약이 없습니다.</td></tr>';
                    } ?>
                </table>
            </article>

            <!-- main4 -->
            <article class="index">
                <div class="page_wrap">
                    <div class="page_nation">
                        <p><?php echo $write_page; ?></p>
                    </div>
                </div>
            </article>
        </main>

        <!-- footer -->
        <?php
            include('./footer.php');
        ?>
    </div>
</body>

</html>