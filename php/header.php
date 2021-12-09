<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";

    $session_id = (isset($_SESSION["session_id"])) ? $_SESSION["session_id"] : null;
?>

<header>
    <div class="header-group">
        <div class="header-center">
            <a href="./main.php">
                <img src="../image/main.png">
            </a>
        </div>
        <div class="header-right">
            <div class="header-right-top">
                <!-- 로그인 했을 경우 -->
                <?php if(isset($session_id)) { ?>
                <a href="./logout.php"><span>로그아웃</span></a>
                <!-- 로그인 하지 않았을 경우 -->
                <?php } else {?>
                <a href="./login.php"><span>로그인&nbsp</span></a>
                <a href="./registration.php"><span>회원가입</span></a>
                <?php } ?>

                <!-- 관리자로 로그인 했을 경우 -->
                <?php if($session_id === "admin") { ?>
                <a href="./adminpage_user.php"><span>관리자 페이지</span></a>
                <!-- 관리자로 로그인 하지 않았을 경우 -->
                <?php } else {?>
                <a href="./mypage_user.php"><span>마이 페이지</span></a>
                <?php } ?>
            </div>
            <div class="header-right-bottom">
                <span>문의전화 02-441-6006</span>
                <span>객실예약 02-441-7007</span>
            </div>
        </div>
    </div>
    <nav>
        <ul>
            <a href="./hotel.php">
                <li>호 텔</li>
            </a>
            <a href="./room_double.php">
                <li>객 실</li>
            </a>
            <a href="./dining.php">
                <li>다이닝</li>
            </a>
            <a href="./facilities.php">
                <li>부대시설</li>
            </a>
            <a href="./service_notice_board.php">
                <li>고객 서비스</li>
            </a>
        </ul>
    </nav>
</header>