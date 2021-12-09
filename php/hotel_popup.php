<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- 카카오 지도 api사용  -->
    <div id="map" style="width:500px; height:400px;"></div>
    <!-- api키 사용 -->
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=	68e8af711c74c952741b25914a3cae6c"></script>
    <script>
        // 현재 위치
        let cur_location = new kakao.maps.LatLng(37.5610288807307, 127.03470402664523)
        var container = document.querySelector('#map'); 
        var options = {
            center: cur_location,
            level: 3 
        };

        var map = new kakao.maps.Map(container, options);

        // 마커가 표시될 위치입니다 s
        var markerPosition = cur_location;

        // 마커를 생성합니다
        var marker = new kakao.maps.Marker({
            position: markerPosition
        });

        // 마커가 지도 위에 표시되도록 설정합니다
        marker.setMap(map);
    </script>

</body>

</html>