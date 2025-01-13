<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>버스 경로 정보</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://oapi.map.naver.com/openapi/v3/maps.js?ncpClientId=nx1puka0ns"></script>
    <style>
        body { 
            margin: 0; 
            padding: 0; 
        }
        #header {
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
        }
        .back-button {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            color: #333; /* 흰색에서 어두운 색으로 변경 */
            font-size: 24px;
            text-decoration: none;
        }
        .back-button:hover {
            color: #666; /* 호버 효과 추가 */
        }
        .bus-number {
            font-size: 40px;
            font-weight: bold;
            margin: 0;
        }
        #wrap { 
            position: relative;
            margin-top: 90px; /* 헤더 높이만큼 여백 추가 */
        }
        #map { 
            width: 100%; 
            height: calc(100vh - 90px); /* 헤더 높이만큼 뺀 나머지 */
        }
        .route-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php
    $servicekey = "z77ZBdChsMxxR8HY78hs5hmHVwO0wZwa7S2NGyV4EfrS2vRy%2BIvbtySGeg%2BpNzXQjz6eGlqNYOXRZj%2F6HrlMkw%3D%3D";
    $citycode = $_GET['citycode'];
    $routeid = $_GET['routeid'];
    $busnum = $_GET['busnum'];
    $routeno = $_GET['routeno'];
    
    // 경유 정류소 목록 조회
    $stationUrl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService/getRouteAcctoThrghSttnList?serviceKey=$servicekey&cityCode=$citycode&routeId=$routeid&numOfRows=10000&pageNo=1&_type=json";
    $stationData = curl($stationUrl);
    // print_r($stationData);

    $routeNoUrl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService/getRouteNoList?serviceKey=$servicekey&cityCode=$citycode&routeNo=$routeno&numOfRows=1000&pageNo=1&_type=json";
    $routeData = curl($routeNoUrl);
    // print_r($routeData);exit;
    $endnodenm = $routeData['response']['body']['items']['item']['endnodenm'];
    $startnodenm = $routeData['response']['body']['items']['item']['startnodenm'];
    ?>

    <div id="header">
        <div class="back-button">
            <a href="javascript:history.back()" style="color: inherit; text-decoration: none;">←</a>
        </div>
        <div class="bus-number"><?php echo $busnum; ?></div>
    </div>

    <div id="wrap">
        <div id="map"></div>
    </div>

    <script>
        // PHP 데이터로 경로 배열 생성
        var polylinePath = [
            <?php
            if ($stationData && isset($stationData['response']['body']['items']['item'])) {
                $items = $stationData['response']['body']['items']['item'];
                $center = intdiv(count($items), 2);
                
                // 데이터 출력하면서 시작점과 끝점 좌표 저장
                foreach ($items as $key => $station) {
                    if (isset($station['gpslati']) && isset($station['gpslong'])) {
                        // JavaScript 배열용 좌표 출력
                        echo "new naver.maps.LatLng(" . $station['gpslati'] . ", " . $station['gpslong'] . ")";
                        if ($key < count($items) - 1) {
                            echo ",\n            ";
                        }
                        
                        // 시작점과 끝점 좌표 저장
                        if ($station['nodenm'] === $startnodenm) {
                            $startPoint = ['lat' => $station['gpslati'], 'lng' => $station['gpslong']];
                        }
                        if ($station['nodenm'] === $endnodenm) {
                            $endPoint = ['lat' => $station['gpslati'], 'lng' => $station['gpslong']];
                        }
                    }
                }
            }
            ?>
        ];

        // 지도 스타일 정의
        var mapOptions = {
            zoom: 11,
            center: new naver.maps.LatLng(<?php echo $items[$center]['gpslati'] . "," . $items[$center]['gpslong']; ?>),
            zoomControl: true,
            zoomControlOptions: {
                position: naver.maps.Position.RIGHT_CENTER
            },
            mapTypeControl: false
        };

        // 지도 생성
        var map = new naver.maps.Map('map', mapOptions);

        // 경로 그리기
        var polyline = new naver.maps.Polyline({
            path: polylinePath,
            strokeColor: '#00B2FF',
            strokeOpacity: 0.8,
            strokeWeight: 5,
            map: map
        });

        // 시작점과 끝점에 마커 추가
        var startPoint = <?php echo json_encode($startPoint); ?>;
        var endPoint = <?php echo json_encode($endPoint); ?>;

        // 시작점 마커 추가
        if (startPoint) {
            var startMarker = new naver.maps.Marker({
                position: new naver.maps.LatLng(startPoint.lat, startPoint.lng),
                map: map,
                icon: {
                    content: '<div style="background-color: #00B2FF; border-radius: 50%; width: 12px; height: 12px; border: 2px solid white;"></div>',
                    anchor: new naver.maps.Point(8, 8)
                }
            });
        }

        // 끝점 마커 추가
        if (endPoint) {
            var endMarker = new naver.maps.Marker({
                position: new naver.maps.LatLng(endPoint.lat, endPoint.lng),
                map: map,
                icon: {
                    content: '<div style="background-color: #00B2FF; border-radius: 50%; width: 12px; height: 12px; border: 2px solid white;"></div>',
                    anchor: new naver.maps.Point(8, 8)
                }
            });
        }
        // 모든 경로가 보이도록 지도 범위 조정
        // var bounds = polylinePath.reduce(function(bounds, latlng) {
        //     return bounds.extend(latlng);
        // }, new naver.maps.LatLngBounds());
        
        // map.fitBounds(bounds);
    </script>
</body>
</html>