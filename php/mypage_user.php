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
                <link rel="stylesheet" href="../css/mypage_user.css">
                <link rel="stylesheet" href="../css/header.css">
                <link rel="stylesheet" href="../css/footer.css">
                <link rel="stylesheet" href="../css/aside.css">
                <script src="../js/mypage_user.js"></script>
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
                                <li><a href="./mypage_user.php"><b>내 정보</b></a></li>
                                <li><a href="./mypage_reservation.php?page=1&option=no">내 예약</a></li>
                                <li><a href="./mypage_inquiry_board.php?option=title&page=1">내 문의</a></li>
                                <li><a href="./mypage_resignation.php">회원탈퇴</a></li>
                            </ul>
                        </div>
                    </aside>
                    <main>
                        <article class="h2">
                            <h2>내 정보</h2>
                        </article>
                        <hr>
                        <article class="name">
                            <table>
                                <tr>
                                    <td>성(영문)</td>
                                    <td><?= $row['last_name'] ?></td>
                                    <td>이름(영문)</td>
                                    <td><?= $row['first_name'] ?></td>
                                </tr>
                                <form action="./mypage_user_update.php" name="user_form" method="post">
                                    <tr class="gender">
                                        <td>성별</td>
                                        <td colspan="3">
                                            <span id="span_gender"><?= $row['gender'] ?></span>
                                            <select name="gender" id="gender" style="display: none" ;>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Ms.">Ms.</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="id_password">
                                        <td>아이디</td>
                                        <td colspan="3">
                                            <span id="id"><?= $row['id'] ?></span>
                                            <div id="check_id"></div>
                                        </td>
                                    </tr>
                                    <tr class="id_password" style="display: none;">
                                        <td>새 비밀번호</td>
                                        <td colspan="3">
                                            <input type="password" name="password" id="password_new" placeholder="새 비밀번호 입력" required>
                                            <div></div>
                                        </td>
                                    </tr>
                                    <tr class="id_password" style="display: none;">
                                        <td>비밀번호 확인</td>
                                        <td colspan="3">
                                            <input type="password" id="password_check" placeholder="비밀번호와 동일하게 입력" required>
                                            <div id="password_failed"></div>
                                        </td>
                                    </tr>
                                    <tr class="email">
                                        <td>이메일</td>
                                        <td colspan="3">
                                            <input type="text" id="email1" name="email1" value="<?= $row['email1'] ?>" readonly required>
                                            <span>@</span>
                                            <input type="text" id="email2" name="email2" value="<?= $row['email2'] ?>" readonly required>
                                            <select name="email" id="email" style="display: none;">
                                                <option value="naver.com">naver.com</option>
                                                <option value="gmail.com">gmail.com</option>
                                                <option value="">직접 입력</option>
                                            </select>
                                            <div></div>
                                        </td>
                                    </tr>
                                    <tr class="phone">
                                        <td>휴대전화</td>
                                        <td colspan="3">
                                            <input type="text" id="mobile1" name="mobile1" value="<?= $row['mobile1'] ?>" readonly required>
                                            <span>-</span>
                                            <input type="text" id="mobile2" name="mobile2" value="<?= $row['mobile2'] ?>" readonly required>
                                            <span>-</span>
                                            <input type="text" id="mobile3" name="mobile3" value="<?= $row['mobile3'] ?>" readonly required>
                                            <div></div>
                                        </td>
                                    </tr>
                            </table>
                        </article>
                        <article class="buttons">
                            <input type="button" id="update" value="수 정">
                            <input type="button" id="cancel" value="취 소" style="display: none;">
                        </article>
                        </form>
                    </main>
                    <?php
                    include('./footer.php');
                    ?>
                </div>
                <script>
                    // 선택한 select 값 저장
                    document.querySelector('#gender option[value="<?= $row['gender'] ?>"]').selected = "selected"
                </script>
            </body>

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