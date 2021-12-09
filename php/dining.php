<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/dining.css">
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
                    <li class="title">다 이 닝</li>
                    <hr>
                    <li><b>레 스 토 랑</b></li>
                </ul>
            </div>
        </aside>

        <!-- article -->
        <main>
            <article class="head">
                <h2>레 스 토 랑</h2>
                <hr>
            </article>
            <article class="main">
                <section class="section1">
                    <img src="../image/dining_restaurant.jpg" />
                </section>
            </article>
            <article class="dining1">
                <hr>
                <h1>운영시간 안내</h1>
                <table>
                    <tr>
                        <!-- 첫번째 줄 시작 -->
                        <td class="title" rowspan="2">조식</td>
                        <td>주중</td>
                        <td>06:30 ~ 09:30</td>
                        <td rowspan="2">성인 23,000원<br>어린이 15,000원</td>
                    </tr><!-- 첫번째 줄 끝 -->
                    <tr>
                        <!-- 두번째 줄 시작 -->
                        <td>주말/공휴일</td>
                        <td>07:00 ~ 10:00</td>
                    </tr><!-- 두번째 줄 끝 -->
                    <tr>
                        <!-- 세번째 줄 시작 -->
                        <td class="title" rowspan="2">중식</td>
                        <td>주중</td>
                        <td>11:30 ~ 14:00</td>
                        <td>성인 19,000원<br>어린이 13,000원</td>
                    </tr><!-- 세번째 줄 끝 -->
                    <tr>
                        <!-- 네번째 줄 시작 -->
                        <td>주말/공휴일</td>
                        <td>12:00 ~ 14:30</td>
                        <td>성인 32,000원<br>어린이 23,000원</td>
                    </tr><!-- 네번째 줄 끝 -->
                    <tr>
                        <!-- 다섯번째 줄 시작 -->
                        <td class="title" rowspan="2">석식</td>
                        <td>주중</td>
                        <td rowspan="2">18:00 ~ 22:00</td>
                        <td>성인 26,000원<br>어린이 20,800원</td>
                    </tr><!-- 다섯번째 줄 끝 -->
                    <tr>
                        <!-- 여섯번째 줄 시작 -->
                        <td>주말/공휴일</td>

                        <td>성인 32,000원<br>어린이 23,000원</td>
                    </tr><!-- 여섯번째 줄 끝 -->
                </table>
            </article>

            <article class="dining2">
                <hr>
                </hr>
                <h1>이용 안내</h1>
                <li>위치<br>
                    &nbsp; - 미래호텔 서울 2층</li>
                <li>예약 및 문의 <br>
                    &nbsp; - 02-441-7007</li>
                <li>좌석 수 - 114석(15인석 규모의 별도 공간 있음)</li>
                <li>주차 안내<br>
                    &nbsp; - 레스토랑 이용 시 3시간 당 5,000원<br>
                    &nbsp; - 주차장 만차 시 인근 외부 주차장 이용 가능</li>
            </article>
        </main>
        <!-- footer -->
        <?php
            include('./footer.php');
        ?>
    </div>
</body>

</html>