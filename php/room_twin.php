<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/room_double.css">
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
          <li class="title">객 실</li>
          <hr>
          <li><a href="./room_double.php">더 블</a></li>
          <li class="title"><a href="./room_twin.php">트 윈</a></li>
          <li><a href="./room_triple.php">트리플</a></li>
          <li><a href="./room_grand.php">그랜드</a></li>
        </ul>
      </div>
    </aside>

    <!-- article -->

    <main>
      <article class="head">
        <h2>트 윈</h2>
      </article>
      <hr>
      <article class="main">
        <section class="section1">
          <img src="../image/room_twin.jpg" />
        </section>
      </article>
      <article class="room1">
        <hr>
        <h3>객실 구성</h3>
        <section class="section2">
          <li>23.1㎡ 크기</li>
          <li>1인 침대 2개</li>
          <li>40'' LED TV(36채널)</li>
          <li>책상</li>
          <li>냉장고</li>
          <li>무료커피, 티백</li>
          <li>무료 생수 2병(1박 기준)</li>
          <li>무료 유무선 인터넷</li>
          <li>유니버셜 어댑터(220V 전용)</li>
          <li>개인금고</li>
          <li>AVEDA 욕실용품</li>
          <li>2개의 목욕 가운</li>
          <hr>
        </section class="section2">
      </article>

      <article class="room2">
        <section class="section3">
          <h3>이용 안내</h3>
          <li>체크인 / 체크아웃 <br>
            &nbsp; - 체크인 : 오후 3시 이후 <br>
            &nbsp; - 체크아웃 : 정오</li>
          <li>주차 안내 <br>
            &nbsp; - 객실 이용 시 1대 무료<br>
            &nbsp; - 추가 시 1대 1박 당 10,000원 추가<br>
            &nbsp; - 만차 시 인근 외부 주차장 이용</li>
          <li>레스토랑 이용 안내<br>
            &nbsp; - 조식 : (주중) 06:30 ~ 09:30 / (주말, 공휴일) 07:00 ~ 10:00<br>
            &nbsp; - 중식 : (주중) 11:30 ~ 14:00 / (주말, 공휴일) 12:00 ~ 14:30<br>
            &nbsp; - 석식 : (주중, 주말, 공휴일) 18:00 ~ 22:00</li>
          <li>예약 변경 및 취소 <br>
            &nbsp;&nbsp;&nbsp; &nbsp; - 객실예약실(02-441-7007) 측에 문의</li>
        </section class="section3">
      </article>
    </main>

    <!-- footer -->
    <?php
            include('./footer.php');
        ?>
  </div>
</body>

</html>