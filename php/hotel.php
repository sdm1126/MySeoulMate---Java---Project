<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/hotel.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="https://kit.fontawesome.com/1980604be0.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <!-- header -->
        <?php
        include('./header.php');
        ?>

        <!-- article -->
        <main>
            <article class="head">
                <h2>호 텔</h2>
            </article>
            <hr>
            <article class="main">
                <h1>소 개</h1>
                <table>
                    <tr>
                        <td class="title">개 관 일</td>
                        <td>2021년 6월 11일</td>
                    </tr>
                    <tr>
                        <td class="title">주 소</td>
                        <td>서울특별시 성동구 왕십리로 303(지번주소: 성동구 행당동 284)<br>
                            <div class="popup">
                                <a id="popup"><i class="fas fa-map-marker-alt">위치 보기</i></a>
                        </td>
    </div>
    </tr>
    <tr>
        <td class="title">대표전화</td>
        <td>02-441-6006</td>
    </tr>
    <tr>
        <td class="title" id="content">객실예약</td>
        <td>02-441-7007</td>
    </tr>
    <tr>
        <td class="title">홈페이지</td>
        <td><a href="http://www.mrhi.or.kr/" target="_blank">http://www.mrhi.or.kr/</a> </td>
    </tr>
    <tr>
        <td class="title">구 조</td>
        <td> 지하 4층 ~ 지상 15층</td>
    </tr>
    <tr>
        <td class="title">객 실 수</td>
        <td title="더블, 트윈, 트리플 30실 그랜드 1실"> 총 91室</td>
    </tr>
    <tr>
        <td class="title">시 설</td>
        <td> 객실, 레스토랑, 피트니스 센터</td>
    </tr>
    </table>
    </article>
    </main>
    <!-- footer -->
    <?php
        include('./footer.php');
        ?>
    </div>
</body>
<script>
// 클릭시 팝업창 열림
document.addEventListener('DOMContentLoaded', function() {
    let pop = document.querySelector('#popup')
    let popup = function() {
        var url = "hotel_popup.php";
        var name = "popup test";
        var option = "width = 500, height = 400, top = 100, left = 200, location = no"
        window.open(url, name, option);
    }
    pop.addEventListener('click', popup)
})
</script>

</html>