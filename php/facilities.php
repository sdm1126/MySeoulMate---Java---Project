<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/facilities.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/aside.css">
</head>

<body>
    <div class="container">
        <!-- header -->
        <?php
            include('./header.php');
        ?>

        <!-- aside -->
        <aside>
            <div>
                <ul>
                    <li class="title">부 대 시 설</li>
                    <hr>
                    <li><b>피트니스센터</b></li>
                </ul>
            </div>
        </aside>

        <!-- article -->

        <main>
            <article class="head">
                <h2>피 트 니 스 센 터</h2>
                <hr>
            </article>
            <article class="main">
                <section class="section1">
                    <img src="../image/facilities_fitness_center.jpg" />
                </section>
            </article>

            <article class="facilities">
                <hr>
                </hr>
                <h1>이용 안내</h1>
                <li>위치<br>
                    &nbsp; - 미래호텔 서울 15층</li>
                <li>시설<br>
                    &nbsp; - 트레드밀 3대, 바이크 1대, 크로스 트레이너 1대, 근력운동머신 3종, 복근 전용 기구, 덤벨 세트 등</li>
                <li>운영시간<br>
                    &nbsp; - 06:00 ~ 23:00</li>
                <li>기타<br>
                    &nbsp; - 객실 이용 시 무료 이용<br>
                    &nbsp; - 안전 상의 이유로 16세 이상 입장 가능합니다.</li>
            </article>
        </main>
        <!-- footer -->
        <?php
            include('./footer.php');
        ?>
    </div>
</body>

</html>