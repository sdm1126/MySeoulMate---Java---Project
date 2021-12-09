<!-- 세션 체크 -->
<?php
include_once('../db/db_connector.php');

if (isset($_SESSION['session_id'])) {

    $id = sql_escape($con, $_SESSION['session_id']);

    if (empty($id)) {
        mysqli_close($con);
        header("location: login.php?error=empty_id");
        exit;
    } else {
        // 아이디가 실제 회원인지 확인
        $sql = "SELECT * FROM user WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        // 아이디가 존재한다면 넘어온 값 점검
        if (mysqli_num_rows($result) > 0) {
  
            if (isset($_POST["content"]) && isset($_POST['mode'])) {

                $mode = $_POST['mode'];
                // 댓글 작성이라면 게시글 PK, 수정또는 삭제라면 댓글 PK
                $no = $_GET['no'];

                // 인젝션 방어
                $content            = sql_escape($con, $_POST['content']);
                $written_date       = date('Y-m-d H:i:s', time());
            
                // 공백 점검
               if (empty($content)) {
                    mysqli_close($con);
                    alert_back('내용이 비어있습니다');
                    exit;
                } else {
                    switch($mode){
                        case '등 록' :
                            // 댓글 테이블에 추가. 게시글 PK를 컬럼으로 추가
                            $sql = " INSERT INTO comment
                                SET id = '$id',
                                content = '$content',
                                inquiry_number = $no,
                                written_date = '$written_date' ";
                            break;

                        case '확 인' :
                            // 게시물 수정. 댓글 PK값으로 찾아서 수정
                            $sql = "UPDATE comment SET content = '$content' WHERE no = $no";
                            break;

                        case '삭 제' :
                            // 게시물 삭제. 댓글 PK값으로 찾아서 삭제
                            $sql = "DELETE FROM comment WHERE no = $no";
                            break;
                    }
                        // 데이터베이스에 넣기
                        $result = mysqli_query($con, $sql) or die('fail' . mysqli_error($con));
                        // 성공시
                        if ($result) {
                            echo "<script>history.back();</script>";
                            mysqli_close($con);
                            return;
                        }     
                }
            } else {
                echo "<script>alert('로그인 후 이용 부탁드립니다.');</script>";
                echo "<script>location.replace('./login.php');</script>";
                mysqli_close($con);
                exit;
            }
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
mysqli_close($con);
?>