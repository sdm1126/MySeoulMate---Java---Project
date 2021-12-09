<!-- ajax로 실행하는 php문 -->
<?php
    include_once('../db/db_connector.php');

    $password = $_GET['q'];
    $mode = $_GET['mode'];
    $id = $_SESSION['session_id'];
    
    if($mode === 'password'){
        
        // 비밀번호 가져오기
        $sql = "SELECT password FROM user WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        // 비밀번호 암호화 해서 비교하기
        $sql = "SELECT UNHEX('" . $row['password'] . "') as password;";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $unhex_password = $row['password'];

        if(!($unhex_password === $password)){
            echo '<span style="color: blue; font-size: 14px;">사용가능한 비밀번호입니다</span>';
        }else{
            echo '<span style="color: red; font-size: 14px;">이미 사용 중인 비밀번호입니다</span>'; 
        }
    }

?>