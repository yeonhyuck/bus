<!Doctype html>
<?php

$servicekey = "z77ZBdChsMxxR8HY78hs5hmHVwO0wZwa7S2NGyV4EfrS2vRy%2BIvbtySGeg%2BpNzXQjz6eGlqNYOXRZj%2F6HrlMkw%3D%3D";

$station = json_decode($_POST['id'], true);

$stationname = json_decode($_POST['station'], true);


$citycode = $station['citycode'];
$routeid = $station['routeid'];
$endstation = $station['endstation'];

$stationUrl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService/getRouteInfoIem?serviceKey=$servicekey&cityCode=$citycode&routeId=$routeid&_type=json";
$stationData = curl($stationUrl);

$item = $stationData['response']['body']['items']['item'];



$startvehicletime = $item['startvehicletime'];
$starttime = substr($startvehicletime, 0, 2) . ":" . substr($startvehicletime, 2);

$endvehicletime = $item['endvehicletime'];
$endtime = substr($endvehicletime, 0, 2) . ":" . substr($endvehicletime, 2);



?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #f5f5f5; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <header style="background-color: white; height: 60px; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
        <a href="javascript:history.back()" style="color: #333; font-size: 24px; text-decoration: none; margin-right: 20px;">←</a>
        <div style="color: #4CAF50; font-size: 20px; font-weight: bold; margin: 0 auto;"><?php echo $station['busnum']; ?></div>
    </header>

    <div style="background-color: white;">
    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
            <span style="margin-right: 5px;">🚌</span>
            운행지역
        </div>
        <div style="font-size: 16px; line-height: 1.5;">
            <?php echo $station['startstation']. "↔" . $station['endstation']; ?>
        </div>
        <div style="color: #666; font-size: 14px; margin-top: 5px;">
        <?php echo $station['cityname']. " ". $station['bustype']; ?>
        </div>
    </div>

    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
            <span style="margin-right: 5px;">🕔</span>
            운행시간
        </div>
        <div style="background: white; padding: 15px; border-radius: 8px;">
            <div style="display: flex; margin-bottom: 8px;">
                <!-- <span style="width: 40px; color: #666; border: 1px solid #ddd; font-size: 10px; text-align: center; padding: 2px 0;">기점</span> -->
                <span style="margin-left: 10px;"><?php echo $starttime . "~" . $endtime ?></span>
            </div>
        </div>
    </div>

    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
            <span style="margin-right: 5px;">⌛</span>
            배차간격
        </div>
        <div style="font-size: 15px;">
        <?php 
            if(isset($item['intervaltime'])) {
                echo "평일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $item['intervaltime'] . "분<br>";
            }
            if(isset($item['intervalsattime'])) {
                echo "토요일 " . $item['intervalsattime'] . "분<br>";
            }
            if(isset($item['intervalsuntime'])) {
                echo "일요일 " . $item['intervalsuntime'] . "분";
            }
        ?>
        </div>
    </div>

    <div style="padding: 20px;">
        <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
            <span style="margin-right: 5px;">🚏</span>
            주요경유지
        </div>
        <div style="font-size: 15px; line-height: 1.6;">
            <?php 
                foreach($stationname as $item) {
                    // print_r($item);exit;
                    echo $item['name'];
                    if( $item['name'] == $endstation) {
                        break;
                    }
                    echo " - ";
                }
            ?>
    </div>
</div>
</body>
</body>
</html>