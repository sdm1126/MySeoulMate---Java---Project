<?php 
    include_once "../db/db_connector.php";
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/service_faq.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/aside.css">
  <link rel="stylesheet" href="../css/page.css">
  <script src="../js/faq.js"></script>
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
          <li><a href="../php/service_notice_board.php">공지사항</a></li>
          <li><a href="../php/service_faq.php"><b>F A Q</b></a></li>
          <li><a href="../php/service_inquiry.php">문의하기</a></li>
        </ul>
      </div>
    </aside>

    <!-- main -->
    <?php
            // 0. current_page 설정
            if(isset($_GET['page'])) {
                $current_page = $_GET['page'];
            } else {
                $current_page = 1;
            }

            if(isset($_GET['option'])){
                $option = $_GET['option'];
            }else{
                $option = 'question';
            }

            if(isset($_GET['search_str'])){
                $search_str = $_GET['search_str'];
            }else{
                $search_str = '';
                $flag = true;
            }

            // 1. 총 게시글 수를 구한다. 
            // sql문은 FAQ 테이블에서 모든 컬럼을 총 수량을 total_count로 처리하는 sql문이다
            $sql = "SELECT COUNT(*) AS `total_count` FROM faq ";
            if($option === 'question'){
                $sql .= " WHERE $option LIKE '%$search_str%' ";   
            }else{
                $sql .= " WHERE question LIKE '%$search_str%' OR answer LIKE '%$search_str%'";      
            }
            // 2. 쿼리문을 실행한다.
            $result = mysqli_query($con, $sql);

            // 3. 배열에 결과값을 담는다.
            $row = mysqli_fetch_array($result);

            // 4. 총 게시글 수를 변수에 옮긴다.
            $total_count = $row['total_count'];

            // 5. 한 페이지에 들어갈 게시글 수를 정한다.
            $page_row = 5;

            // 6. 총 페이지 수를 구한다.
            // ceil(): 올림 ex) 21개 글, 4페이지 → 21/4 = 5.xxx = 6(올림) → 전체가 6페이지가 됨!
            $total_page = ceil($total_count / $page_row);

            // 7. 해당 페이지에 몇번째 글부터 띄울건지를 정함 
            /* 
            글번호 0~4(5개) -> current page 1페이지
            글번호 5~9(5개) -> current page 2페이지
            글번호 10~14(5개) -> current page 3페이지
            */
            $from_record = ($current_page - 1) * $page_row;

            if($option === 'question'){
                $sql = "WHERE  $option LIKE '%$search_str%' ";
            }else{
                $sql = "WHERE question LIKE '%$search_str%' OR answer LIKE '%$search_str%'";
            }
           
            $sql = "SELECT * FROM faq " .$sql. " ORDER BY no LIMIT {$from_record}, {$page_row} ";
            $result = mysqli_query($con, $sql);
            // 8. 빈 배열을 생성한다.
            $list = array();

            // 9. 해당되는 페이지 글을 가져온다.
            
            $result = mysqli_query($con, $sql);
            // if (!$result) {
            //     echo 'MySQL Error: ' . mysqli_error($con);
            //     exit;
            // }

            for($i = 0; $row = mysqli_fetch_array($result); $i++) {
                $list[$i] = $row;
                $list[$i]['no'] = $total_count - $from_record - $i;
            }

            // 10. start_page, end_page
            $start_page = (int)($current_page / $page_row) * $page_row  + 1;
            $end_page = ($start_page + $page_row - 1) < $total_page ? ($start_page + $page_row - 1) < $total_page : $total_page;

            // 11. url
            $url = './service_faq.php?page=';

            // 12. 함수 실행
            $index_page = get_paging($page_row, $current_page, $total_page, $url);
        ?>

    <main>
      <!-- main1 -->
      <div class="h2">
        <h2>F A Q</h2>
      </div>
      <hr>

      <!-- main2 -->
      <form action="./service_faq.php" method="get">
        <select name="option" id="select">
          <option value="question">질문 </option>
          <option value="answer">질문 및 답변</option>
        </select>
        <input type="text" id="search_str" name="search_str">
        <input type="submit" value="검 색" id="submit" onsubmit="return false">
        <span id="error" style="color: red; font-size: 14px;"></span>
      </form>

      <!-- main3 -->
      <div class="table">
        <table>
          <tr>
            <th id="type">구 분</th>
            <th id="content">내 용</th>
            </style=>
            <!-- PHP문 반복 시작 -->
            <?php  
                        $sql = "SELECT * FROM faq ";
                        $result = mysqli_query($con, $sql);

                        for($i = 0; $i < count($list); $i++) { ?>
          <tr>
            <td>Q</td>
            <td><a class="<?= $i?>" href="#"><?= $list[$i]['question']; ?></a></td>
          </tr>
          <tr class="A<?= $i?>" style="display:none;">
            <td>A</td>
            <td><b class="<?= $i?>" href="#"><?= $list[$i]['answer']; ?></td>
          </tr>
          <?php } ?>
          <!-- PHP문 반복 끝 -->
        </table>
      </div>

      <!-- main4 -->
      <article class="index">
        <div class="page_wrap">
          <div class="page_nation">
            <p><?php echo $index_page; ?></p>
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
  // 1. question의 객체를 전부 찾는다 for문으로 몇개를 생성했는지 모르기 때문에  let Q = document.querySelectorAll('table td a')
  let Q = document.querySelectorAll('table td a')

  // 2. for으로 반복문을 생성해서 1단계에서 찾은 question의 갯수만큼 question 개별객체를 불러온다
  for (let i = 0; i < Q.length; i++) {
    // 3. 찾은 question객체에 하나씩 이벤트리스너를 걸어준다 클릭했을때 임시함수(function)을 호출하는 이벤트 리스너
    Q[i].addEventListener('click', function(e) {
      // 4. answer.style.display가 none일 때 보이게하는 코드 ===연산자로 타입과 값이 같음
      if (document.querySelector('.A' + i).style.display === '') {
        document.querySelector('.A' + i).style.display = 'none'
      } else {
        document.querySelector('.A' + i).style.display = ''
      }
    })
  }
  </script>
</body>

</html>