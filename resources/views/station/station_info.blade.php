<!DOCTYPE html>
<html>
<?php

$servicekey = "z77ZBdChsMxxR8HY78hs5hmHVwO0wZwa7S2NGyV4EfrS2vRy%2BIvbtySGeg%2BpNzXQjz6eGlqNYOXRZj%2F6HrlMkw%3D%3D";

$citycode = $_GET['citycode'];
// echo $citycode;exit;

$busnodeidurl = "http://apis.data.go.kr/1613000/BusSttnInfoInqireService/getSttnNoList?serviceKey=$servicekey&cityCode=$citycode&nodeNm=%EC%82%AC%EC%86%A1%EB%8F%99.%EC%A2%85%EC%A0%90&numOfRows=10&pageNo=1&_type=json";
$busnodeidurlData = curl($busnodeidurl);

// print_r($busnodeidurlData);exit;

if ($busnodeidurlData) {
    $nodeid = $busnodeidurlData['response']['body']['items']['item'][0]['nodeid'];
    $nodeno = $busnodeidurlData['response']['body']['items']['item'][0]['nodeno'];
    $nodenm = $busnodeidurlData['response']['body']['items']['item'][0]['nodenm'];
}
// print_r($busnodeidurlData);exit;

// echo $nodeid;exit;


$stationurl = "https://apis.data.go.kr/1613000/BusSttnInfoInqireService/getSttnThrghRouteList?serviceKey=$servicekey&cityCode=$citycode&nodeid=$nodeid&numOfRows=10&pageNo=1&_type=json";
$stationurlData = curl($stationurl);

// print_r($stationurlData['response']['body']['items']['item']);exit;

if ($stationurlData) {
    foreach ($stationurlData['response']['body']['items']['item'] as $key => $node) {
        if(isset($node['routeno']) && isset($node['routeid'])) {
            $station[] = array(
                'routeno' => $node['routeno'],
                'startnodenm' => $node['startnodenm'],
                'routeid' => $node['routeid'],
                'nodeid' => $nodeid,
                'nodeno' => $nodeno
            );
        }
    }
}

// print_r($station);exit;

$mapurl = "http://apis.data.go.kr/1613000/BusSttnInfoInqireService/getSttnNoList?serviceKey=$servicekey&cityCode=$citycode&nodeNm=".urlencode($nodenm)."&numOfRows=10&pageNo=1&_type=json";
// echo $mapurl;
$mapurlData = curl($mapurl);

// print_r($mapurlData);exit;
if ($mapurlData) {
    $map = array();
    if(isset($mapurlData['response']['body']['items']['item'][0])) {
        $item = $mapurlData['response']['body']['items']['item'][0];
        if(isset($item['gpslati']) && isset($item['gpslong'])) {
            $gpslati = $mapurlData['response']['body']['items']['item'][0]['gpslati'];
            $gpslong = $mapurlData['response']['body']['items']['item'][0]['gpslong'];
        }
    }
}

// echo $gpslati;exit;


?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 전체 스타일 */
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        /* 헤더 스타일 */
        .header {
            background-color: #f5f5f5;
            color: #000;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .station-number {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .station-name {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .station-direction {
            font-size: 16px;
            color: #666;
            margin: 10px 0;
        }

        /* 버스 목록 스타일 */
        .bus-list {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .bus-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .bus-number {
            color: #00B894;
            font-size: 24px;
            font-weight: bold;
            margin-right: 15px;
            min-width: 80px;
        }

        .bus-direction {
            color: #666;
            font-size: 14px;
            flex: 1;
        }

        .arrival-info {
            position: absolute;
            right: 20px;
            display: flex;
            align-items: center;
        }

        .arrival-time {
            width: 24px;
            height: 24px;
            margin-left: 10px;
        }

        /* 상단 네비게이션 */
        .nav-top {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .back-button {
            font-size: 24px;
            color: #000;
            text-decoration: none;
        }

        .home-button {
            font-size: 24px;
            color: #000;
            text-decoration: none;
        }

        /* 기능 버튼 */
        .function-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .function-button {
            padding: 10px 30px;
            border-radius: 20px;
            border: 1px solid #ddd;
            background: white;
            font-size: 14px;
            color: #333;
        }

        /* 새로고침 버튼 */
        .refresh-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 25px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
            font-size: 20px;
        }

        .modal_map {
            position: relative;
            width: 100%;
            height: 100%;
            background: white;
        }

        .modal.show {
            display: block;
        }
        .modal{
        position:absolute;
        display:none;
        
        justify-content: center;
        top:0;
        left:0;

        width:100%;
        height:100%;

        

        background-color: rgba(0,0,0,0.4);
    }
    .modal_body{
        position:absolute;
        top:50%; 
    

        width:1400px;
        height:750px;

        padding:40px;

        text-align: center;

        background-color: rgb(255,255,255);
        border-radius:10px; 
        box-shadow:0 2px 3px 0 rgba(34,36,38,0.15);

        transform:translateY(-50%);
        }
    </style>
</head>
<body>
<header style="background-color: #00BFA5; height: 250px; padding-top: 20px; position: relative;">
    <div style="position: absolute; top: 20px; left: 20px;">
    <a href="javascript:history.back()" style="color: white; font-size: 24px; text-decoration: none;">←</a>
    </div>
    <div class="container" style="width: 90%; max-width: 500px; margin: 0 auto; color: white; text-align: center;">
        <div id="city" style="font-size: 16px; margin-bottom: 15px;">
            <?php echo $station[0]['nodeno'];?>
        </div>
        <div id="number" style="font-size: 40px; font-weight: bold; margin-bottom: 15px;">
            <?php echo $station[0]['startnodenm'];?>
        </div>
        <div id="route" style="font-size: 14px; margin-bottom: 25px;">
            <?php echo $station[0]['startnodenm']; echo "&nbsp방면"; ?> 
        </div>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button type="button" id="map" class="btn" style="border: 1px solid rgba(255,255,255,0.5); color: white; background: transparent; padding: 8px 25px; border-radius: 20px; font-size: 14px;">
                🗺 지도
            </button>
        </div>
    </div>
</header>

    <!-- 버스 목록 -->
    <div class="bus-list">
        <?php foreach ($station as $bus): ?>
        <div class="bus-item">
            <div class="bus-number"><?php echo $bus['routeno']; ?></div>
            <div class="bus-direction"><?php echo $bus['startnodenm']; ?> 방향</div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="modal">
        <div class="modal_map"></div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=nx1puka0ns&submodules=geocoder"></script>
    <script>
    const nodenm = "<?php echo $nodenm; ?>";
    const gpslati = "<?php echo $gpslati; ?>";
    const gpslong = "<?php echo $gpslong; ?>";
    const modal = document.querySelector('.modal');

    // 디버깅을 위한 로그

    $(document).ready(function() {
        $("#map").click(function() {
            $('.modal_map').html(`
                <header style="background-color: white; height: 60px; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
                    <a href="javascript:void(0)" onclick="javascript:modal.style.display='none'" style="color: #333; font-size: 24px; text-decoration: none; margin-right: 20px;">←</a>
                    <div style="color: #4CAF50; font-size: 20px; font-weight: bold; margin: 0 auto;">${nodenm}</div>
                </header>
                <div style="background-color: white; padding: 20px;">
                    <div id="map_div" style="width: 100%; height: 600px; border-radius: 8px;"></div>
                </div>
            `);
            
            modal.style.display = "flex";

            // 좌표값 확인
            if (gpslati && gpslong) {
                try {
                    // 네이버 지도 초기화
                    const position = new naver.maps.LatLng(parseFloat(gpslati), parseFloat(gpslong));
                    const mapOptions = {
                        center: position,
                        zoom: 15
                    };
                    
                    const naverMap = new naver.maps.Map('map_div', mapOptions);
                    
                    // 마커 생성
                    const marker = new naver.maps.Marker({
                        position: position,
                        map: naverMap
                    });

                    // 정보창 생성
                    const infoWindow = new naver.maps.InfoWindow({
                        content: `
                            <div style="padding: 10px;">
                                <strong>${nodenm}</strong><br>
                                정류장번호: <?php echo $nodeno; ?>
                            </div>
                        `
                    });

                    // 마커 클릭 시 정보창 표시
                    naver.maps.Event.addListener(marker, 'click', function() {
                        if (infoWindow.getMap()) {
                            infoWindow.close();
                        } else {
                            infoWindow.open(naverMap, marker);
                        }
                    });
                } catch (error) {
                    console.error('Map initialization error:', error);
                    document.getElementById('map_div').innerHTML = '지도를 불러오는 중 오류가 발생했습니다.';
                }
            } else {
                document.getElementById('map_div').innerHTML = '위치 정보를 불러올 수 없습니다.';
            }
        });

        // 모달 닫기
        $(document).on('click', '.modal', function(e) {
            if ($(e.target).closest('.modal_map').length === 0) {
                modal.style.display = 'none';
            }
        });
    });
</script>
    <!-- 새로고침 버튼 -->
    <button class="refresh-button" onClick="window.location.reload()">↻</button>
</body>
</html>