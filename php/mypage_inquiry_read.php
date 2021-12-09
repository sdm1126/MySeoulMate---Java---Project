<!-- 세션체크 -->
<?php
include_once('../db/db_connector.php');
if (isset($_SESSION['session_id'])) {

    $id = sql_escape($con, $_SESSION['session_id']);

    if (empty($id)) {
        mysqli_close($con);
        header("location: login.php?error=user_id_empty");
        exit;
    } else {
        // 아이디 체크
        $sql = "SELECT * FROM user WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        // 아이디가 데이터베이스에 있다면
        if (mysqli_num_rows($result) > 0) {

            // 게시글 테이블(inquiry)의 'no'값을 가져옴
            $list_number = $_GET['no'];

            $full_name = $row['full_name'];
            $mobile = $row['mobile1'] . '-' . $row['mobile2'] . '-' . $row['mobile3'];
            $email = $row['email1'] . '@' . $row['email2'];

            // 게시글 불러오기
            $sql = "SELECT * FROM inquiry WHERE no = $list_number";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);

            // 게시글 작성자와 접속한 아이디가 같다면
            if ($row['id'] === $id) {
                $title = $row['title'];
                $content = $row['content'];
                $attached_file = ($row['attached_file'] !== "")? ($row['attached_file']) : "";

                // 댓글 불러오기 게시글 번호를 컬럼으로 준 뒤 그 값을 가지고 있는 값들을 부름
                $sql = "SELECT * FROM comment WHERE inquiry_number = $list_number";
                $result = mysqli_query($con, $sql);
                mysqli_close($con);

                // 결과들을 배열에 저장
                $comment = array();
                for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
                    $comment[$i] = $row;
                }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/mypage_inquiry_read.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/aside.css">
  <script src="../js/mypage_inquiry_read.js"></script>
</head>

<body>
  <div class="container">
    <?php
                        include('./header.php');
                        ?>
    <aside>
      <div>
        <ul>
          <li class="title">마이 페이지</li>
          <hr>
          <li><a href="./mypage_user.php">내 정보</a></li>
          <li><a href="./mypage_reservation.php">내 예약</a></li>
          <li><a href="./mypage_inquiry_board.php?option=title&page=1"><b>내 문의</b></a></li>
          <li><a href="./mypage_resignation.php">회원탈퇴</a></li>
        </ul>
      </div>
    </aside>
    <main>
      <article class="h2">
        <h2>내 문의</h2>
      </article>
      <hr>
      <article class="form">
        <!-- 게시글 불러오기 -->
        <form action="./mypage_inquiry_update.php?no=<?= $list_number ?>" method="post">
          <table>
            <tr>
              <td class="title">성명</td>
              <td><?= $full_name ?></td>
            </tr>
            <tr>
              <td class="title">아이디</td>
              <td><?= $id ?></td>
            </tr>
            <tr>
              <td class="title">제목</td>
              <td><input name="title" type="text" id="title" value="<?= $title ?>" readonly></input></td>
            </tr>
            <tr>
              <td class="title">내용</td>
              <td><textarea name="content" id="content" cols="30" rows="10" readonly><?= $content ?></textarea></td>
            </tr>
            <tr>
              <td class="title">휴대전화</td>
              <td><?= $mobile ?></td>
            </tr>
            <tr>
              <td class="title">이메일</td>
              <td><?= $email ?></td>
            </tr>
            <tr>
              <?php
                                            if($attached_file !== ""){
                                            ?>
              <td class="title">첨부 파일</td>
              <td><a href="../php/download_files.php?file=<?=$attached_file?>">다운로드</a></td>
              <?php }
                                            ?>
            </tr>
          </table>
      </article>
      <article class="button">
        <div>
          <input type="button" value="수 정" id="btn_modify" name="mode">
          <input type="button" value="삭 제" id="btn_delete" name="mode">
        </div>
        </form>
      </article>
      <!-- 댓글 불러오기 -->
      <?php
                            for ($i = 0; $i < count($comment); $i++) {
                            ?>
      <article class="textarea">
        <h3><?= $comment[$i]['id'] ?></h3>
        <!-- 댓글 테이블의 PK값을 작성된 댓글의 no값으로 한다-->
        <form action="./mypage_comment_update.php?no=<?= $comment[$i]['no'] ?>" method="post">
          <textarea name="content" class="comment_content" cols="30" rows="10"
            readonly><?= $comment[$i]['content'] ?></textarea>
      </article>
      <article class="reply">
        <div>
          <input type="button" class="comment_update" name="mode" value="수 정">
          <input type="button" class="comment_delete" name="mode" value="삭 제">
        </div>
        </form>
      </article>
      <?php }
                            ?>
      <article class="textarea">
        <h3>답 변</h3>
        <!-- 작성될 댓글 어느 게시글에 작성될지 모르기 때문에 게시글 PK를 값으로 준다 -->
        <form action="./mypage_comment_update.php?no=<?= $list_number ?>" method="post">
          <textarea name="content" id="" cols="30" rows="10"></textarea>
      </article>
      <article class="reply">
        <div>
          <input type="submit" id="comment_insert" name="mode" value="등 록">
        </div>
        </form>
      </article>
    </main>
    <?php
                        include('./footer.php');
                        ?>
  </div>
</body>

</html>
<?php
            } else {
                echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
                echo "<script>location.replace('./login.php');</script>";
                mysqli_close($con);
                exit;
            }
        } else {
            echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
            echo "<script>location.replace('./login.php');</script>";
            mysqli_close($con);
            exit;
        }
    }
} else {
    echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
    echo "<script>location.replace('./login.php');</script>";
    mysqli_close($con);
    exit;
}
?>