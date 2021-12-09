<!-- 체크인/체크아웃, 상품, 타입, 조식 인원 수 선택하여 가격 조회하는 기능(Ajax 통신 사용) -->
<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";
    
    $q = $_GET['q'];
    $q_array = explode("_", $q);

    $check_in = $q_array[0];
    $check_out = $q_array[1];
    $prev_check_out = date("Y-m-d", strtotime("-1 days", strtotime($check_out)));
    $room_night = date_diff(new DateTime($check_in), new DateTime($check_out)) -> days;
    $adult_breakfast = $q_array[2];
    $child_breakfast = $q_array[3];
    $deal_name = $q_array[4];
    $room_type = $q_array[5];

    switch($deal_name) {
        case "Room Only":         $discount_rate_deal_name = "discount_rate_room_only"; break;
        case "Relaxing Package":  $discount_rate_deal_name = "discount_rate_relaxing";  break;
        case "Streaming Package": $discount_rate_deal_name = "discount_rate_streaming"; break;
        case "Everland Package":  $discount_rate_deal_name = "discount_rate_everland";  break;
        case "Winter Package":    $discount_rate_deal_name = "discount_rate_winter";    break;
        case "Christmas Package": $discount_rate_deal_name = "discount_rate_christmas"; break;
    }

    switch($room_type) {
        case "더블":   $tariff_room_type = "tariff_double"; break;
        case "트윈":   $tariff_room_type = "tariff_twin";   break;
        case "트리플": $tariff_room_type = "tariff_triple"; break;
        case "그랜드": $tariff_room_type = "tariff_grand";  break;
    }

    $sql = "SELECT SUM($tariff_room_type * (1 - $discount_rate_deal_name / 100)) AS total_tariff_excluding_breakfast FROM tariff WHERE tariff_date BETWEEN '$check_in' AND '$prev_check_out'";
    $result = mysqli_query($con, $sql); 
    $row = mysqli_fetch_array($result);

    $total_tariff_excluding_breakfast = $row['total_tariff_excluding_breakfast'];
    $total_tariff = intval($total_tariff_excluding_breakfast) + ((intval($adult_breakfast) * 19800 * $room_night) + (intval($child_breakfast) * 10000 * $room_night));

    echo '<h2>총 '.number_format($total_tariff, -3).'원(VAT 포함)'.' / '.$room_night.'박</h2>';

    // 데이터 전송용 input(hidden)
    echo '<input type="text" name="total_tariff" value="'.$total_tariff.'" hidden/>';
    echo '<input type="text" name="room_night" value="'.$room_night.'" hidden/>';

    echo '<input type="submit" value="다 음"/>';
?>