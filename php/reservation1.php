<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";
    
    // 1. 로그인 체크
    if (!isset($_SESSION["session_id"]) && !isset($_SESSION["session_name"])) {
        echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
        echo "<script>location.replace('./login.php');</script>";
        mysqli_close($con);
        exit();
        
    } else {
        $id = $_SESSION["session_id"];
        $full_name = $_SESSION["session_name"];
    }

    // 2. isset 체크
    if(isset($_POST["check_in"])  && 
       isset($_POST["check_out"])) {
            
        // 3. injection 체크
        $check_in        = mysqli_real_escape_string($con, $_POST["check_in"]);
        $check_out       = mysqli_real_escape_string($con, $_POST["check_out"]);

        //  4. strlen 체크
        if     (!strlen($check_in))  { alert_back("체크인 데이터가 존재하지 않습니다.");    mysqli_close($con); exit(); } 
        else if(!strlen($check_out)) { alert_back("체크아웃 데이터가 존재하지 않습니다.");  mysqli_close($con); exit(); }
        
    } else {
        echo "<script>alert('잘못된 접근입니다. 다시 시도 부탁드립니다.');</script>";
        echo "<script>location.replace('./main.php');</script>";
        mysqli_close($con);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/reservation1.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../js/reservation1.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!-- JQuery UI CSS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script> <!-- JQuery JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> <!-- JQuery UI JS -->
</head>

<body onload="focus_check_in()">
    <div class="container">
        <!-- header -->
        <?php
            include_once('./header.php');
        ?>

        <!-- article -->
        <form action="reservation2.php" method="post">
            <article>
                <div class="article-group">
                    <section class="section1">
                        <h2>예 약</h2>
                        <span>※ 다중 객실 예약은 객실예약실(02-441-7007)로 문의 부탁드립니다.</span>
                    </section>

                    <section class="section2">
                        <h3>예약 세부정보</h3>
                    </section>

                    <section class="section3">
                        <!-- 1. 일정 선택 -->
                        <div class="section3-1">
                            <h3>일정 선택</h3>
                            <fieldset>
                                <span>체크인</span>
                                <?php if(isset($check_in)) { ?>
                                <input type="text" id="check_in" name="check_in" value=<?php echo $check_in ?> />
                                <?php } else { ?>
                                <input type="text" id="check_in" name="check_in" placeholder="일자 선택" />
                                <?php } ?>

                                <span>체크아웃</span>
                                <?php if(isset($check_out)) { ?>
                                <input type="text" id="check_out" name="check_out" value=<?php echo $check_out ?> />
                                <?php } else { ?>
                                <input type="text" id="check_out" name="check_out" placeholder="일자 선택" />
                                <?php } ?>
                            </fieldset>
                        </div>

                        <!-- 2. 인원 선택 -->
                        <div class="section3-2">
                            <h3>인원 선택</h3>
                            <fieldset>
                                <span>성인</span>
                                <input type="number" id="adult" name="adult" value="2" min="0" />
                                <span>소아</span>
                                <input type="number" id="child" name="child" value="0" min="0" />
                            </fieldset>
                            <span>※ 객실당 총 4인까지 투숙 가능합니다.</span>
                        </div>

                        <!-- 3. 상품 선택 -->
                        <div class="section3-3">
                            <h3>상품 선택</h3>
                            <div class="section3-3-sub">
                                <!-- Ajax 통신으로 reservation1_search_deal.php로부터 값 받기 -->
                            </div>
                        </div>

                        <!-- 4. 옵션 선택 -->
                        <div class="section3-4">
                            <h3>옵션 선택</h3>
                            <fieldset>
                                <span>성인 조식</span>
                                <input type="number" id="adult_breakfast" name="adult_breakfast" value="0" min="0" />
                                <span>소아 조식</span>
                                <input type="number" id="child_breakfast" name="child_breakfast" value="0" min="0" />
                            </fieldset>
                            <span>
                                ※ 투숙 시 조식 요금은 1박 당 성인 1인 19,800원, 소아 1인 10,000원입니다.<br>
                                ※ 37개월 미만의 유아 동반 시 조식에 대한 요금은 무료입니다.
                            </span>
                        </div>
                    </section>

                    <section class="section4">
                        <!-- Ajax 통신으로 reservation1_search_tariff.php로부터 값 받기 -->
                        <h4 style="color:red">상품, 타입을 선택해주세요.</h4>
                    </section>
                </div>
            </article>
        </form>

        <!-- footer -->
        <?php
            include_once('./footer.php');
        ?>
    </div>
</body>

</html>