<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/theMiraeSeoul/db/create_statement.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!-- JQuery UI CSS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script> <!-- JQuery JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> <!-- JQuery UI JS -->
</head>

<body onload="start_slide()">
    <div class="container">
        <!-- header -->
        <?php
            include_once('./header.php');
        ?>

        <!-- article -->
        <article>
            <div class="article-group">
                <section class="section1">
                    <section class="section1_slides">
                        <a href="#"><img src="../image/main_lobby.jpg" alt="slide1" /></a>
                        <a href="#"><img src="../image/main_restaurant.jpg" alt="slide2" /></a>
                        <a href="#"><img src="../image/room_grand.jpg" alt="slide3" /></a>
                    </section>
                    <section class="section1_nav">
                        <a class="prev">prev</a>
                        <a class="next">next</a>
                    </section>
                    <section class="section1_indicator">
                        <a class="activated">1</a>
                        <a>2</a>
                        <a>3</a>
                    </section>
                </section>

                <form id="form" action="./reservation1.php" method="post">
                    <section class="section2">
                        <span>체크인</span>
                        <input type="text" id="check_in" name="check_in" placeholder="일자 선택" />
                        <span>체크아웃</span>
                        <input type="text" id="check_out" name="check_out" placeholder="일자 선택" />
                        <input type="button" id="button" value="예 약" />
                        <section class="section2-sub"></section>
                    </section>
                </form>

                <?php
                    $sql = "SELECT * FROM deal";
                    $result = mysqli_query($con, $sql);
                    
                    $list = array();
                    for($i = 0; $row = mysqli_fetch_array($result); $i++) {
                        $list[$i] = $row;
                    }

                    // 가장 최근 상품 인덱스 설정
                    $latest_index = count($list) - 1;
                ?>
                <section class="section3">
                    <?php for($i = 0; $i < 3; $i++) { ?>
                    <section class="section3-sub">
                        <img src=<?php echo $list[$latest_index - $i]['deal_image'] ?> /><br>
                        <span><?php echo $list[$latest_index - $i]['deal_name'] ?></span><br>
                        <span><?php echo $list[$latest_index - $i]['deal_content'] ?></span><br>
                        <span><?php echo $list[$latest_index - $i]['deal_start'] ?> ~
                            <?php echo $list[$latest_index - $i]['deal_end'] ?></span>
                    </section>
                    <?php } ?>
                </section>
            </div>
        </article>

        <!-- footer -->
        <?php
            include_once('./footer.php');
        ?>
    </div>
</body>

</html>