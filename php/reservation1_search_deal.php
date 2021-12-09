<!-- 체크인/체크아웃 선택하여 상품 조회하는 기능(Ajax 통신 사용) -->
<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";
    
    $flag = false;

    $q = $_GET['q'];
    $q_array = explode("_", $q);
    $check_in = $q_array[0];
    $check_out = $q_array[1];
    $prev_check_out = date("Y-m-d", strtotime("-1 days", strtotime($check_out)));
    $room_night = date_diff(new DateTime($check_in), new DateTime($check_out)) -> days;

    // 1. 요금 등록 여부 체크
    $sql = "SELECT * FROM tariff WHERE tariff_date BETWEEN '$check_in' AND '$prev_check_out'";
    $result = mysqli_query($con, $sql);
    if(mysqli_num_rows($result) !== ($room_night)) {
        echo "<h4 style = 'color: red'>예약 불가 일자가 포함되어있습니다.</h4>";
    } else {
        $flag = true;
    }
    
    if($flag === true) {
        
        // 2. 상품 판매기간 체크
        $sql = "SELECT * FROM deal WHERE '$prev_check_out' BETWEEN deal_start AND deal_end";
        $result = mysqli_query($con, $sql);
        $list = array();

        // 3. 객실 수량 체크
        $sql_double = "SELECT * FROM inventory WHERE inventory_double = 0 AND (inventory_date BETWEEN '$check_in' AND '$prev_check_out')";
        $result_double = mysqli_query($con, $sql_double);
        $inventory_double = mysqli_num_rows($result_double); // 선택일자 중 더블 만실 일자 수

        $sql_twin = "SELECT * FROM inventory WHERE inventory_twin = 0 AND (inventory_date BETWEEN '$check_in' AND '$prev_check_out')";
        $result_twin = mysqli_query($con, $sql_twin);
        $inventory_twin = mysqli_num_rows($result_twin);  // 선택일자 중 트윈 만실 일자 수

        $sql_triple = "SELECT * FROM inventory WHERE inventory_triple = 0 AND (inventory_date BETWEEN '$check_in' AND '$prev_check_out')";
        $result_triple = mysqli_query($con, $sql_triple);
        $inventory_triple = mysqli_num_rows($result_triple);  // 선택일자 중 트리플 만실 일자 수
        
        $sql_grand = "SELECT * FROM inventory WHERE inventory_grand = 0 AND (inventory_date BETWEEN '$check_in' AND '$prev_check_out')";
        $result_grand = mysqli_query($con, $sql_grand);
        $inventory_grand = mysqli_num_rows($result_grand);  // 선택일자 중 그랜드 만실 일자 수
        
        for($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
            $list[$i] = $row;

            echo "<fieldset>";
            echo '<div class="section3-3-group">';
            
            // left
            echo '<div class="section3-3-left">';
            echo "<img src="."\"".$list[$i]['deal_image']."\""."/>";
            echo "</div>";
            
            // center
            echo '<div class="section3-3-center">';
            echo '<h3 id="deal_name'.$i.'">'.$list[$i]['deal_name'].'</h3>';
            echo "<h4>".$list[$i]['deal_content']."</h4>";
            if($i === 0) {
                echo "<h4>".$list[$i]['deal_start']." ~ 상시"."</h4>";
            } else {
                echo "<h4>".$list[$i]['deal_start']." ~ ".$list[$i]['deal_end']."</h4>";
            }
            echo "</div>";

            // right
            echo '<div class="section3-3-right">';
            echo '<label '.(($inventory_double > 0) ? "hidden" : "").'><input type="radio" class="room_type" name="deal_info" value="'.$list[$i]['deal_name'].'_'.'더블">더블</label><br>';
            echo '<label '.(($inventory_twin   > 0) ? "hidden" : "").'><input type="radio" class="room_type" name="deal_info" value="'.$list[$i]['deal_name'].'_'.'트윈">트윈</label><br>';
            echo '<label '.(($inventory_triple > 0) ? "hidden" : "").'><input type="radio" class="room_type" name="deal_info" value="'.$list[$i]['deal_name'].'_'.'트리플">트리플</label><br>';
            echo '<label '.(($inventory_grand  > 0) ? "hidden" : "").'><input type="radio" class="room_type" name="deal_info" value="'.$list[$i]['deal_name'].'_'.'그랜드">그랜드</label><br>';
            echo "</div>";

            echo "</div>";
            echo "</fieldset>";
        }
    }
?>