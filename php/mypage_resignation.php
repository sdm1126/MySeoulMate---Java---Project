<!-- 세션 체크 -->
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
                <link rel="stylesheet" href="../css/mypage_resignation.css">
                <link rel="stylesheet" href="../css/header.css">
                <link rel="stylesheet" href="../css/footer.css">
                <link rel="stylesheet" href="../css/aside.css">
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
                                <li><a href="./mypage_reservation.php?page=1&option=no">내 예약</a></li>
                                <li><a href="./mypage_inquiry_board.php?option=title&page=1">내 문의</a></li>
                                <li><a href="./mypage_resignation.php"><b>회원탈퇴</b></a></li>
                            </ul>
                        </div>
                    </aside>
                    <main>
                        <article class="h2">
                            <h2>회원탈퇴</h2>
                        </article>
                        <hr>
                        <article class="warning">
                            <h3>회원탈퇴 신청 전, 아래의 유의사항을 한번 더 확인해주시기 바랍니다.</h3>
                            <ul>
                                <li>개인정보보호법에 따라 호텔 이용기록, 개인 정보, 문의 내역 모두 삭제됩니다.</li>
                                <li>탈퇴 신청이 완료되면 즉시 홈페이지 로그인이 제한됩니다.</li>
                            </ul>
                        </article>
                        <article class="button">
                            <input type="button" id="submit" value="탈 퇴">
                        </article>
                    </main>
                    <?php
                    include('./footer.php');
                    ?>
                </div>
            </body>
            <script>
                // 회원탈퇴 php파일로 이동
                let submit = document.querySelector('#submit')
                submit.addEventListener('click', function() {
                    if (confirm("회원님의 모든 정보가 사라집니다. 탈퇴하시겠습니까?")) {
                        location.replace('./mypage_resignation_delete.php');
                    }
                })
            </script>

            </html>
<?php
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