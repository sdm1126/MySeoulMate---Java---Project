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
    if(isset($_POST["check_in"])        && 
       isset($_POST["check_out"])       && 
       isset($_POST["adult"])           && 
       isset($_POST["child"])           && 
       isset($_POST["deal_info"])       &&                                     
       isset($_POST["adult_breakfast"]) && 
       isset($_POST["child_breakfast"]) &&
       isset($_POST["total_tariff"])    && 
       isset($_POST["room_night"])) {
            
        // 3. injection 체크
        $check_in        = mysqli_real_escape_string($con, $_POST["check_in"]);
        $check_out       = mysqli_real_escape_string($con, $_POST["check_out"]);
        $adult           = mysqli_real_escape_string($con, $_POST["adult"]);
        $child           = mysqli_real_escape_string($con, $_POST["child"]);
        $deal_info       = mysqli_real_escape_string($con, $_POST["deal_info"]);
        $adult_breakfast = mysqli_real_escape_string($con, $_POST["adult_breakfast"]);
        $child_breakfast = mysqli_real_escape_string($con, $_POST["child_breakfast"]);
        $total_tariff    = mysqli_real_escape_string($con, $_POST["total_tariff"]);
        $room_night      = mysqli_real_escape_string($con, $_POST["room_night"]);

        //  4. strlen 체크
        if     (!strlen($check_in))        { alert_back("체크인 데이터가 존재하지 않습니다.");    mysqli_close($con); exit(); } 
        else if(!strlen($check_out))       { alert_back("체크아웃 데이터가 존재하지 않습니다.");  mysqli_close($con); exit(); } 
        else if(!strlen($adult))           { alert_back("성인 인원 데이터가 존재하지 않습니다."); mysqli_close($con); exit(); }
        else if(!strlen($child))           { alert_back("소아 인원 데이터가 존재하지 않습니다."); mysqli_close($con); exit(); }
        else if(!strlen($deal_info))       { alert_back("상품별 데이터가 존재하지 않습니다.");    mysqli_close($con); exit(); } 
        else if(!strlen($adult_breakfast)) { alert_back("성인 조식 인원 데이터가 존재하지 않습니다."); mysqli_close($con); exit(); }
        else if(!strlen($child_breakfast)) { alert_back("소아 조식 인원 데이터가 존재하지 않습니다."); mysqli_close($con); exit(); } 
        else if(!strlen($total_tariff))    { alert_back("총 금액 데이터가 존재하지 않습니다.");   mysqli_close($con); exit(); } 
        else if(!strlen($room_night))      { alert_back("박 수 데이터가 존재하지 않습니다.");     mysqli_close($con); exit(); } 
        
        // 1~4 통과 시
        else {
            $reservation1_data = array(
                $id,
                $full_name,
                $check_in,
                $check_out,
                $adult,
                $child,
                $deal_info,
                $adult_breakfast,
                $child_breakfast,
                $total_tariff,
                $room_night
            );
            mysqli_close($con);
        }
        
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
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/reservation2.css">
    <script src="../js/reservation2.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!-- JQuery UI CSS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script> <!-- JQuery JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> <!-- JQuery UI JS -->
</head>

<body>
    <div class="container">
        <!-- header -->
        <?php
            include_once('./header.php');
        ?>

        <!-- article -->
        <form action="reservation_insert.php" method="post">
            <article>
                <div class="article-group">
                    <section class="section1">
                        <h2>예 약</h2>
                    </section>

                    <section class="section2">
                        <h3>신용카드 정보</h3>
                        <table>
                            <tr>
                                <td>카드 종류<em>*</em></td>
                                <td>
                                    <select id="cc_company" name="cc_company">
                                        <option>AMEX</option>
                                        <option>BC</option>
                                        <option>CITI BANK</option>
                                        <option>DINERS</option>
                                        <option>HYUNDAI</option>
                                        <option>JCB</option>
                                        <option>AMEX</option>
                                        <option>KEB</option>
                                        <option>AMEX</option>
                                        <option>KOOKMIN</option>
                                        <option>LOTTE</option>
                                        <option>AMEX</option>
                                        <option>MASTER OVERSEAS</option>
                                        <option>SHINHAN</option>
                                        <option>SAMSUNG</option>
                                        <option>VISA OVERSEAS</option>
                                        <option>UNION PAY</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>카드 번호<em>*</em></td>
                                <td>
                                    <input type="text" id='cc_number' name='cc_number' maxlength="19" required
                                        numberOnly value autocomplete="off" placeholder="카드번호 입력" />
                                </td>
                            </tr>
                            <tr>
                                <td>유효 기간<em>*</em></td>
                                <td>
                                    <select name='cc_expiry_month'>
                                        <option>01</option>
                                        <option>02</option>
                                        <option>03</option>
                                        <option>04</option>
                                        <option>05</option>
                                        <option>06</option>
                                        <option>07</option>
                                        <option>08</option>
                                        <option>09</option>
                                        <option>10</option>
                                        <option>11</option>
                                        <option>12</option>
                                    </select> /
                                    <select name='cc_expiry_year'>
                                        <option>2022</option>
                                        <option>2023</option>
                                        <option>2024</option>
                                        <option>2025</option>
                                        <option>2026</option>
                                        <option>2027</option>
                                        <option>2028</option>
                                        <option>2029</option>
                                        <option>2030</option>
                                        <option>2031</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <span>
                            ※ 위 정보는 투숙을 개런티하기 위한 용도 외에는 어떤 목적으로도 사용되지 않습니다.<br>
                            ※ 체크카드 및 일부 신용카드의 경우 사용이 제한될 수 있습니다.<br>
                            ※ 온라인 예약 시 직접 결제가 이루어지지 않으며, 최종 결제는 프론트 데스크에서 해주시기 바랍니다.
                        </span>
                    </section>

                    <section class="section3">
                        <h3>추가 요청 사항</h3>
                        <textarea id="special_request" name="special_request" maxlength="250"
                            placeholder="250자 이내로 작성 부탁드립니다."></textarea><br>
                        <span>
                            ※ 객실 컨디션은 당일 객실 상황에 따라 달라질 수 있습니다.<br>
                            ※ 전 객실은 금연실로 운영 중입니다.
                        </span>
                    </section>

                    <section class="section4">
                        <h3>유의 사항</h3>
                        <span>
                            ※ 체크인 시 등록카드 작성 및 투숙객 본인 확인을 위해 본인 사진이 포함된 신분증을 반드시 제시해 주시기 바랍니다.<br>
                            ※ 안내견을 제외한 애완견 등 동물 입장은 불가합니다.<br>
                            ※ 부모를 동반하지 않은 만 19세 미만 미성년자는 투숙할 수 없습니다.(청소년 보호법 제 30조/제 58조)<br>
                            ※ 객실 이용 시 객실 당 1대 무료로 주차 가능합니다.(체크아웃 당일 오후 1시까지)<br>
                            ※ 자세한 객실 안내는 객실예약실(02-441-7007)로 문의 바랍니다.
                        </span>
                    </section>

                    <section class="section5">
                        <h3>취소 및 환불 규정</h3>
                        <span>
                            ※ 모든 취소 및 환불 요청은 객실예약실(02-441-7007)로 문의 바랍니다. <br>
                            ※ 숙박예정일 1일 전 18시 이전: 위약금 없이 취소 가능<br>
                            ※ 숙박예정일 1일 전 18시 이후: 1박 요금의 80% 부과
                        </span>
                    </section>

                    <section class="section6">
                        <!-- 데이터 전송용 textarea(hidden) -->
                        <?php for($i = 0; $i < count($reservation1_data); $i++) { ?>
                        <!-- value를 ''로 감싸주지 않을 경우, 공백 이후는 value와 관계 없는 다른 attribute로 인식되어 값이 소실되므로 주의 -->
                        <input type="text" name="reservation1_data[]" value='<?php echo $reservation1_data[$i]; ?>'
                            hidden>
                        <?php } ?>

                        <input id="submit" type="submit" value="예 약"></input>
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