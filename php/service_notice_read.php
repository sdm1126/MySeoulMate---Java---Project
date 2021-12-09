<?php
  include_once $_SERVER['DOCUMENT_ROOT']."/TheMiraeSeoul/db/db_connector.php";
  $mode = $_GET['mode'];
  $title = isset($_GET['title']) ? $_GET['title'] : "";
  $content = "";

  if($mode === '글읽기') {
    $no = isset($_GET['no']) ? $_GET['no'] : ""; // 글읽기 모드일 때만 'no' 체크
    $sql = "select * from notice where no = '$no' ";
    $update_query = "update notice set read_count = read_count+1 where no= $no";
    $view_query = mysqli_query($con, $update_query); 
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $title = $row['title'];
    $content = $row['content'];
    $written_date = $row['written_date'];

  }else if($mode === '글쓰기') {
    $written_date = date('Y-m-d H:i:s', time());
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/service_notice_read.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/aside.css">
  <script src="../js/service_notice_read.js"></script>
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
      <article class="h2">
        <h2>공 지 사 항</h2>
      </article>
      <hr>

      <!-- main2 -->
      <article class="form">
        <form method="post" action="service_notice_crud.php">
          <table>
            <input type="hidden" value="<?= $no?>" name="no">
            <tr>
              <!-- 에러메세지 출력 -->
              <?php if(isset($_GET['error'])){ ?>
              <div id="check" style="color: red"><?= $_GET['error']; ?></div>
              <?php } ?>

              <!-- 관리자로 로그인 시 -->
              <?php if(isset($_SESSION['session_id'])&& $_SESSION['session_id'] === 'admin'){?>
              <td class="title"><b>제 목</b></td>
              <td><input type="text" id="title" name="title" value="<?php echo $title; ?>" required></td>
            </tr>

            <tr>
              <td class="title"><b>작 성 일 자</b></td>
              <td>
                <input type="text" id="currentDate" name="written_date" value="<?php echo $written_date?>" readonly>
              </td>
            </tr>

            <tr>
              <td class="title"><b>내 용</b></td>
              <td><textarea id="content" name="content" cols="30" rows="10"><?php echo $content; ?></textarea>
              </td>
            </tr>

            <!-- 관리자로 로그인 안했을 시 -->
            <?php }else {?>
            <tr>
              <td class=" title"><b>제 목</b></td>
              <td><input type="text" name="title" value="<?php echo $title; ?>" readonly></td>
            </tr>

            <tr>
              <td class="title"><b>작 성 일 자</b></td>
              <td>
                <input type="text" id="currentDate" name="written_date" value="<?php echo $written_date?>" readonly>
              </td>
            </tr>

            <tr>
              <td class="title" id="content"><b>내 용</b></td>
              <td><textarea name="content" cols="30" rows="10" readonly><?php echo $content; ?></textarea></td>
            </tr>
            <?php } ?>
          </table>
      </article>

      <!-- main3 -->
      <article class="button">
        <div>
          <!-- 관리자로 로그인 시 -->
          <?php if(isset($_SESSION['session_id'])&& $_SESSION['session_id'] === 'admin') { ?>
          <!-- 글쓰기 모드일 시-->
          <?php if($mode === '글쓰기') { ?>
          <input type="submit" class='submit' value="작 성" name="mode">
          <!-- 글읽기 모드일 시-->
          <?php } else if($mode === '글읽기') { ?>
          <input type="submit" class='submit' value="수 정" name="mode">
          <input type="submit" value="삭 제" name="mode">
          <?php } ?>
          <?php } ?>
        </div>
        </form>
      </article>
    </main>

    <!-- footer -->
    <?php
       include('./footer.php');
    ?>
  </div>
</body>

</html>