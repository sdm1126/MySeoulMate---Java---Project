<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";

    // 1. 로그인 체크
    if (!isset($_SESSION["session_id"]) && !isset($_SESSION["session_name"])) {
        echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
        echo "<script>location.replace('./login.php');</script>";
        mysqli_close($con);
        exit(); 
    }
    
    // 2. isset 체크
    if(isset($_POST["reservation1_data"]) && 
       isset($_POST["cc_company"])        && 
       isset($_POST["cc_number"])         && 
       isset($_POST["cc_expiry_month"])   && 
       isset($_POST["cc_expiry_year"])    &&                                     
       isset($_POST["special_request"])) {
            
        // 2-1. 배열 해체
        $reservation1_data = $_POST["reservation1_data"];
        $id = $reservation1_data[0];
        $full_name = $reservation1_data[1];
        $check_in = $reservation1_data[2];
        $check_out = $reservation1_data[3];
        $adult = $reservation1_data[4];
        $child = $reservation1_data[5];
        $deal_info = $reservation1_data[6];
            $deal_info_array = explode("_", $deal_info);
            $deal_name = $deal_info_array[0];
            $room_type = $deal_info_array[1];
        $adult_breakfast = $reservation1_data[7];
        $child_breakfast = $reservation1_data[8];
        $total_tariff = $reservation1_data[9];
        $room_night = $reservation1_data[10];

        // 3. injection 체크
        $cc_company      = mysqli_real_escape_string($con, $_POST["cc_company"]);
        $cc_number       = mysqli_real_escape_string($con, $_POST["cc_number"]);
        $cc_expiry_month = mysqli_real_escape_string($con, $_POST["cc_expiry_month"]);
        $cc_expiry_year  = mysqli_real_escape_string($con, $_POST["cc_expiry_year"]);
        $special_request = mysqli_real_escape_string($con, $_POST["special_request"]);

        // 4. strlen 체크
        if     (!strlen($cc_company))      { alert_back("카드사 데이터가 존재하지 않습니다.");        mysqli_close($con); exit(); } 
        else if(!strlen($cc_number))       { alert_back("카드번호 데이터가 존재하지 않습니다.");      mysqli_close($con); exit(); } 
        else if(!strlen($cc_expiry_month)) { alert_back("유효기간(월) 데이터가 존재하지 않습니다.");  mysqli_close($con); exit(); }
        else if(!strlen($cc_expiry_year))  { alert_back("유효기간(년) 데이터가 존재하지 않습니다.");  mysqli_close($con); exit(); }
        else if(!strlen($special_request)) { alert_back("특별 요청사항 데이터가 존재하지 않습니다."); mysqli_close($con); exit(); }
        
        // 1~4 통과 성공 시
        else {
            // 5. 회원 정보 체크
            $sql = "SELECT * FROM user WHERE id = '$id'";
            $select_result = mysqli_query($con, $sql);
            
            // 5-1. 회원 정보가 존재하지 않을 경우
            if(mysqli_num_rows($select_result) === 0) {
                echo "<script>alert('회원 정보가 존재하지 않습니다.');</script>";
                echo "<script>location.replace('./registration.php');</script>";
                mysqli_close($con);
                exit();
            
            // 5-2. 회원 정보가 존재할 경우
            } else {
                $sql = "CALL `reservation_insert_procedure`(
                    null, 
                    '$id', 
                    '$full_name', 
                    '$check_in',
                    '$check_out', 
                    $adult, 
                    $child, 
                    '$deal_name', 
                    '$room_type', 
                    $adult_breakfast, 
                    $child_breakfast,
                    $total_tariff, 
                    $room_night,
                    '$cc_company',
                    '$cc_number',
                    '$cc_expiry_month',
                    '$cc_expiry_year',
                    '$special_request',
                    now()
                );";

                // 6. 예약 정상 추가 체크
                $insert_result = mysqli_query($con, $sql);
                
                // 6-1. 예약이 정상 추가되었을 경우
                if($insert_result) {
                    echo "<script>alert('예약이 정상적으로 완료되었습니다.');</script>";
                    echo "<script>location.replace('./main.php');</script>";
                    mysqli_close($con);
                    exit();

                // 6-2. 예약이 정상 추가되지 않았을 경우
                } else {
                    echo "<script>alert('예약이 정상적으로 완료되지 않았습니다. 다시 시도 부탁드립니다.');</script>";
                    echo "<script>location.replace('./main.php');</script>";
                    mysqli_close($con);
                    exit();
                }
            }
        }

    // isset 체크 통과 실패 시
    } else {
        echo "<script>alert('예약이 정상적으로 완료되지 않았습니다. 다시 시도 부탁드립니다.');</script>";
        echo "<script>location.replace('./main.php');</script>";
        mysqli_close($con);
        exit();
    }
?>