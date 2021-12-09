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

            if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST['mode'])) {

                $mode = $_POST['mode'];

                // 인젝션 방어 앞 뒤 공백 제거
                $title              = sql_escape($con, trim($_POST['title']));
                $content            = sql_escape($con, trim($_POST['content']));
                $written_date       = date('Y-m-d H:i:s', time());

                // 공백 점검
                if (empty($title) || empty($content)) {
                    mysqli_close($con);
                    // 뒤로가기 + 새로 고침 기능을 한꺼번에
                    echo "<script>alert('제목 또는 내용이 비어있습니다')</script>";
                    echo "<script>location.href = document.referrer;</script>";
                    exit;
                } else {
                    // POST로 넘어온 mode값에 따라  query문 변경
                    switch ($mode) {

                        case '등 록':

                            $upload_dir = "../data/";
                            //홍길동 : a.hwp 20211207111923077_a.hwp, 저길동 : a.hwp => 20211207111923089_a.hwp 
                            if (!is_dir($upload_dir)) {
                                // 읽기, 쓰기, 실행권한 주기 유저 - 그룹 - 삼자 순으로 준다. 7은 2진법으로 111
                                if (!mkdir($upload_dir, 0777)) {
                                    die("업로드 디렉토리 생성에 실패 했습니다.");
                                };
                            }

                            if ($_FILES['upfile']['name'] === "" && $_FILES['upfile']['error'] !== "") {
                                $upfile_name = "";
                                $upfile_type = "";
                                $copied_file_name = "";
                                $file_name = "";
                            } else {
                                //1. 파일배열에서 5개 항목을 받는다. 
                                $upfile_name = $_FILES["upfile"]["name"];
                                $upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
                                $upfile_type = $_FILES["upfile"]["type"];
                                // 안되면 php ini에서 최대 크기 수정!
                                $upfile_size = $_FILES["upfile"]["size"];
                                $upfile_error = $_FILES["upfile"]["error"];

                                //2. 파일을 파일명과 확장자를 분리시킨다.(memo.sql) => ['memo','sql']
                                $file = explode(".", $upfile_name);
                                $file_name = $file[0];
                                $file_ext = $file[1];

                                //3. 서버에 저장할 파일명을 중복되지 않기 하기위해서 날짜명_시간_파일명.확장자 만든다.
                                $new_file_name = date("Y_m_d_H_i_s");
                                $new_file_name = $new_file_name . "_" . $file_name;
                                // 2021_12_07_11_35_20_memo.sql
                                $copied_file_name = $new_file_name . "." . $file_ext;
                                // ./data/2020_09_23_11_10_20_memo.sql 다 합친것
                                $uploaded_file = $upload_dir . $copied_file_name;

                                //1메가 이상이면 받지 않겠다. 
                                if ($upfile_size > 1000000) {
                                    echo "<script>alert('용량이 1MB를 초과했습니다')</script>";
                                    echo "<script>location.href = document.referrer;</script>";
                                    exit();
                                }

                                //
                                if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
                                    echo "<script>alert('파일 업로드 실패')</script>";
                                    echo "<script>location.href = document.referrer;</script>";
                                    exit();
                                }
                            }

                            $sql = " INSERT INTO inquiry
                                SET id  = '$id',
                                title   = '$title',
                                content = '$content',
                                attached_file = '$copied_file_name',
                                written_date  = '$written_date' ";
                            break;

                        case '확 인':
                            $no = $_GET['no'];
                            $sql = "UPDATE inquiry SET title = '$title', content = '$content' WHERE no = $no";
                            break;

                        case '삭 제':
                            $no = $_GET['no'];
                            $sql = "SELECT attached_file FROM inquiry WHERE no = $no";
                            $result = mysqli_query($con, $sql) or die('fail' . mysqli_error($con));
                            $row = mysqli_fetch_assoc($result);

                            $delete_file = $row['attached_file'];
                            if($delete_file !== ""){
                                unlink("../data/".$delete_file);
                            }

                            $sql = "DELETE FROM inquiry WHERE no = $no";
                            break;
                    }
                    // 데이터베이스에 넣기
                    $result = mysqli_query($con, $sql) or die('fail' . mysqli_error($con));
                    // 성공시
                    if ($result) {
                        echo "<script>location.replace('./mypage_inquiry_board.php?option=title&page=1');</script>";
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
