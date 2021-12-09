<?php
include_once('../db/db_connector.php');
if (isset($_SESSION['session_id'])) {

    $id = sql_escape($con, $_SESSION['session_id']);

    if (empty($id)) {
        mysqli_close($con);
        header("location: login.php?error=user_id_empty");
        exit;
    } else {
        $sql = "SELECT * FROM user WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0) {
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
  <style>
  a:hover {
    color: black;
    text-decoration: none;
  }
  </style>
</head>

<body>
  <div class="container">
    <?php
                    include('./header.php');
                    ?>
    <aside>
      <div>
        <ul>
          <li class="title">관리자
            <br>페이지
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
        <h2>내 문의</h2>
        <?php
                            $list_number = $_GET['no'];
                            $mb_id = $_GET['id'];

                            $sql = "SELECT * FROM user WHERE id = '$mb_id'";
                            $result = mysqli_query($con, $sql);
                            $row = mysqli_fetch_assoc($result);

                            $full_name = $row['full_name'];
                            $mobile = $row['mobile1'] . '-' . $row['mobile2'] . '-' . $row['mobile3'];
                            $email = $row['email1'] . '@' . $row['email2'];

                            $sql = "SELECT * FROM inquiry WHERE no = $list_number";
                            $result = mysqli_query($con, $sql);
                            $row = mysqli_fetch_assoc($result);

                            $title = $row['title'];
                            $content = $row['content'];
                            $attached_file = ($row['attached_file'] !== "") ? ($row['attached_file']) : "";

                            $sql = "SELECT * FROM comment WHERE inquiry_number = $list_number";
                            $result = mysqli_query($con, $sql);

                            $result = mysqli_query($con, $sql);
                            $list = array();
                            for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
                                $list[$i] = $row;
                            }
                            ?>
      </article>
      <hr>
      <article class="form">
        <form action="./mypage_inquiry_update.php?no=<?= $list_number ?>" method="post">
          <table>
            <tr>
              <!-- 첫번째 줄 시작 -->
              <td class="title">성명</td>
              <td><?= $full_name ?></td>
            </tr><!-- 첫번째 줄 끝 -->
            <tr>
              <!-- 첫번째 줄 시작 -->
              <td class="title">아이디</td>
              <td><?= $mb_id ?></td>
            </tr><!-- 첫번째 줄 끝 -->
            <tr>
              <!-- 두번째 줄 시작 -->
              <td class="title">제목</td>
              <td><input name="title" type="text" id="title" value="<?= $title ?>" readonly></input></td>
            </tr><!-- 두번째 줄 끝 -->
            <tr>
              <!-- 두번째 줄 시작 -->
              <td class="title">내용</td>
              <td><textarea name="content" id="content" cols="30" rows="10" readonly><?= $content ?></textarea></td>
            </tr><!-- 두번째 줄 끝 -->
            <tr>
              <!-- 두번째 줄 시작 -->
              <td class="title">휴대전화</td>
              <td><?= $mobile ?></td>
            </tr><!-- 두번째 줄 끝 -->
            <tr>
              <!-- 두번째 줄 시작 -->
              <td class="title">이메일</td>
              <td><?= $email ?></td>
            </tr><!-- 두번째 줄 끝 -->
            <tr>
              <?php
                                        if ($attached_file !== "") {
                                        ?>
              <td class="title">첨부 파일</td>
              <td><a href="../php/download_files.php?file=<?= $attached_file ?>">다운로드</a></td>
              <?php }
                                        ?>
            </tr>
          </table>
      </article>
      <article class="button">
        <div>
          <input type="hidden" value="수 정" id="btn_modify" name="mode">
          <input type="hidden" value="삭 제" id="btn_delete" name="mode">
        </div>
        </form>
      </article>
      <?php
                        for ($i = 0; $i < count($list); $i++) {
                        ?>
      <article class="textarea">
        <h3><?= $list[$i]['id'] ?></h3>
        <form action="./mypage_comment_update.php?id=<?= $mb_id ?>&no=<?= $list[$i]['no'] ?>" method="post">
          <textarea name="content" class="comment_content" cols="30" rows="10"
            readonly><?= $list[$i]['content'] ?></textarea>
      </article>
      <article class="reply">
        <div>
          <input type="button" class="comment_update" name="mode" value="수 정">
          <input type="button" class="comment_delete" name="mode" value="삭 제">
        </div>
        </form>
      </article>
      <?php }
                        mysqli_close($con); // 데이터베이스 접속 종료
                        ?>
      <article class="textarea">
        <<<<<<< HEAD <h3>답 변</h3>
          =======
          <h3>댓 글</h3>
          >>>>>>> origin/master
          <form action="./mypage_comment_update.php?id=<?= $mb_id ?>&no=<?= $list_number ?>" method="post">
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
  <script>
  let btn_modify = document.querySelector('#btn_modify')
  let btn_delete = document.querySelector('#btn_delete')
  let title = document.querySelector('#title')
  let content = document.querySelector('#content')

  let comment_insert = document.querySelector('#comment_insert')
  let comment_update = document.querySelectorAll('.comment_update')
  let comment_delete = document.querySelectorAll('.comment_delete')
  let comment_content = document.querySelectorAll('.comment_content')

  function delete_button_change(button) {
    if (button.value === "삭 제") {
      if (confirm('정말 삭제하시겠습니까?')) {
        button.type = "submit";
      }
    } else if (button.value === "취 소") {
      location.reload()
    }
  }

  btn_modify.addEventListener('click', () => {
    if (btn_modify.value === "수 정") {
      btn_modify.value = "확 인"
      btn_delete.value = "취 소"
      title.readOnly = false
      content.readOnly = false
    } else if (btn_modify.value === "확 인") {
      btn_modify.type = "submit"
    }
  })

  btn_delete.addEventListener('click', () => {
    delete_button_change(btn_delete)
  })

  for (let i = 0; i < comment_update.length; i++) {
    comment_update[i].addEventListener('click', () => {
      if (comment_update[i].value === "수 정") {
        comment_update[i].value = "확 인"
        comment_delete[i].value = "취 소"
        comment_content[i].readOnly = false
      } else if (comment_update[i].value === "확 인") {
        comment_update[i].type = "submit"
      }
    })

    comment_delete[i].addEventListener('click', function() {
      delete_button_change(comment_delete[i])
    })
  }
  </script>
</body>

</html>
<?php
        }
    }
}
?>