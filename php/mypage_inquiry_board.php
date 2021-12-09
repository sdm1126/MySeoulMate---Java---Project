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
            $option = $_GET['option'];
?>
            <!DOCTYPE html>
            <html lang="ko">

            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <link rel="stylesheet" href="../css/mypage_inquiry_board.css">
                <link rel="stylesheet" href="../css/header.css">
                <link rel="stylesheet" href="../css/footer.css">
                <link rel="stylesheet" href="../css/page.css">
                <link rel="stylesheet" href="../css/aside.css">
                <script src="../js/mypage_inquiry_board.js"></script>
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
                                <li><a href="./mypage_inquiry_board.php?option=title&page=1"><b>내 문의</b></a></li>
                                <li><a href="./mypage_resignation.php">회원탈퇴</a></li>
                            </ul>
                        </div>
                    </aside>
                    <main>
                        <div class="h2">
                            <h2>내 문의</h2>
                        </div>
                        <?php
                        include_once('./mypage_inquiry_load.php');
                        ?>
                        <hr>
                        <div class="search">
                            <form action="./mypage_inquiry_board.php" method="get">
                                <select name="option" id="select">
                                    <option value="title">제목</option>
                                    <option value="written_date">작성일</option>
                                </select>
                                <input type="text" id="search_str" name="search_str">
                                <input type="submit" value="조 회" id="submit" onsubmit="return false">
                                <span id="error" style="color: red; font-size: 14px;"></span>
                            </form>
                        </div>
                        <div class="table">
                            <table>
                                <th>번호</th>
                                <th>제목</th>
                                <th>작성일</th>
                                <!-- 테이블 생성 GET방식으로 데이터 넘겨주기 -->
                                <?php
                                for ($i = 0; $i < count($list); $i++) {
                                ?>
                                    <tr>
                                        <td><?= $list[$i]['num'] ?></td>
                                        <td>
                                            <?= '<a class="link_title" href="mypage_inquiry_read.php?no=' . $list[$i]['no'] . '">' . $list[$i]['title'] . '</a>' ?>
                                        </td>
                                        <td><?= substr($list[$i]['written_date'], 0, 10) ?></td>
                                    </tr>
                                <?php }
                                mysqli_close($con);
                                if (count($list) == 0) {
                                    echo '<tr><td colspan="9">등록된 문의가 없습니다.</td></tr>';
                                }
                                ?>
                            </table>
                            <br>
                            <div class="page_wrap">
                                <div class="page_nation">
                                    <p><?= $index_page ?></p>
                                </div>
                            </div>
                        </div>
                    </main>
                    <?php
                    include('./footer.php');
                    ?>
                </div>
            </body>
            <script>
                // 검색한 조건을 기억해서 선택 유지하기
                document.querySelector('option[value="<?= $option ?>"]').selected = "selected"
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