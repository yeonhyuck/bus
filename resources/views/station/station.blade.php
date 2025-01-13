<!Doctype html>

<head>
    <style>
    .station-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .station-list {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
    }

    .station-list::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #FFD700;
    }

    .station-item {
        position: relative;
        padding: 15px 15px 15px 50px;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
    }

    .station-point {
        position: absolute;
        left: 16px;
        width: 12px;
        height: 12px;
        background-color: white;
        border: 2px solid #FFD700;
        border-radius: 50%;
        z-index: 1;
    }

    .station-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
    }

    .station-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .station-num {
        font-size: 14px;
        color: #666;
    }

    .station-time {
        font-size: 13px;
        color: #888;
    }

    .bus-icon {
        position: absolute;
        left: 8px;
        width: 28px;
        height: 28px;
        z-index: 2;
    }

    .bus-badge {
        position: absolute;
        left: -80px;
        top: 10px;
        background-color: #fff;
        border: 1px solid #ff6b6b;
        color: #ff6b6b;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .bus-icon {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;  /* 크기 수정 */
        height: 40px;
        z-index: 2;
    }

    .bus-badge {
        position: absolute;
        left: -90px;
        background-color: #fff;
        border: 1px solid #ff6b6b;
        color: #ff6b6b;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        top: 50%;
        transform: translateY(-50%);
        white-space: nowrap;
    }

    .station-list::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #FFD700;
    }

    .station-point {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        background-color: white;
        border: 2px solid #FFD700;
        border-radius: 50%;
        z-index: 1;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=nx1puka0ns&submodules=geocoder"></script>

</head>
<body style="background-color: #f5f5f5;">

<input type="hidden" name="citycode" value="{{ $citycode }}">
<input type="hidden" name="cityname" value="{{ $cityname }}">
<input type="hidden" name="routeno" value="{{ $routeno }}">
<input type="hidden" name="buscity" value="{{ $cityname }}">
<input type="hidden" name="routeid" value="">
<input type="hidden" name="endstation" value="">


<header style="background-color: #00BFA5; height: 250px; padding-top: 20px; position: relative;">
    <div style="position: absolute; top: 20px; left: 20px;">
    <a href="javascript:history.back()" style="color: white; font-size: 24px; text-decoration: none;">←</a>
    </div>
    <div class="container" style="width: 90%; max-width: 500px; margin: 0 auto; color: white; text-align: center;">
        <div id="city" style="font-size: 16px; margin-bottom: 15px;"></div>
        <div id="number" style="font-size: 40px; font-weight: bold; margin-bottom: 15px;"></div>
        <div id="route" style="font-size: 14px; margin-bottom: 25px;"></div>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button type="button" id="info" class="btn" style="border: 1px solid rgba(255,255,255,0.5); color: white; background: transparent; padding: 8px 25px; border-radius: 20px; font-size: 14px; cursor: pointer;">
                ⓘ 정보
            </button>
            <button type="button" id="map" class="btn" style="border: 1px solid rgba(255,255,255,0.5); color: white; background: transparent; padding: 8px 25px; border-radius: 20px; font-size: 14px;">
                🗺 지도
            </button>
        </div>
    </div>
</header>

<div class="station-container">
    <ul id="station-list" class="station-list">
    </ul>
</div>

<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <button type="button" onClick="window.location.reload()" class="btn btn-light rounded-circle shadow" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
        ↻
    </button>
</div>
<div class="modal">
    <div class="modal_body">
    <div class="modal_map">
    </div>
</div>
<script>
    function closeModal() {
        document.querySelector('.modal').style.display = 'none';
    }

    var citycode = $('input[name=citycode]').val();
    var cityname = $('input[name=cityname]').val();
    var routeno = $('input[name=routeno]').val();
    var buscity = $('input[name=buscity]').val();
    var encoded = {};
    var station = {};
    const modal = document.querySelector('.modal');
    const btnOpenModal=document.querySelector('.btn-open-modal');
    $(document).ready(function(){
        $.ajax({
            type: 'get', 
            dataType: "json", 
            url: '/station/ax_search', 
            data: { "citycode": citycode, "cityname": cityname, "routeno": routeno, "buscity": buscity}, 
            success: function(data) {
                // console.log(data)
                $('#city').text( $('input[name=buscity]').val() +" "+ data.encoded.bustype);
                encoded = data.encoded;
                station = data.encoded2;
                $('#number').text(data.encoded.busnum);
                $('#route').text(data.encoded.startstation +" "+ data.encoded.endstation);
                resultHtml = "";

                $('input[name=routeid]').val(data.encoded.routeid)
                $('input[name=endstation]').val(data.encoded.endstation)

                for( item in data.encoded2) {
                    if (data.encoded2[item]['num']) {
                        resultHtml += '<li class="station-item">';
                        if (typeof data.encoded2[item].location != 'undefined') {
                            // console.log(data.encoded2[item].location);
                            resultHtml +=  `
                            <div class="bus-badge">${data.encoded2[item].location.vehicleno}</div>
                            <img src="assets/img/bus.png" alt="Bus" class="bus-icon" style="width: 30px; height: 30px;">
                            `;
                        }
                        resultHtml += `
                            <div class="station-point"></div>
                            <div class="station-info">
                            <a href="/station_info?citycode=${citycode}" class="station-name">${item}</a>
                            <div class="station-details">
                            <span class="station-num">${data.encoded2[item].num}</span>
                            </div>
                            </div>
                            </li>
                        `;
                    }
                };
                $('#station-list').html(resultHtml);
                $("#info").click(function(){
                    $.ajax({
                        type: 'get', 
                        dataType: "json", 
                        url: '/bus/ax_search_info', 
                        data: { "routeid":  $('input[name=routeid]').val(), 
                            "endstation":  $('input[name=endstation]').val(), 
                            "citycode": $('input[name=citycode]').val()
                        }, 
                        success: function(data) {
                            let startTime = data.startvehicletime || "";
                            let endTime = data.endvehicletime || "";
                            
                            if (startTime) {
                                startTime = startTime.toString().slice(0,2) + ":" + startTime.toString().slice(2);
                            }
                            if (endTime) {
                                endTime = endTime.toString().slice(0,2) + ":" + endTime.toString().slice(2);
                            }
                            station_name = "";
                            for (name in station) {
                                if (station_name !== "") {
                                    station_name += " - ";
                                }
                                station_name += name;
                                if (name == encoded.endstation) {
                                    break;
                                }
                            }
                            intervalsuntime =intervalsattime = intervaltime = ""
                            if (typeof data.intervaltime != "undefined") {
                                intervaltime = `평일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.intervaltime}분<br>`;
                            }
                            if (typeof data.intervalsattime != "undefined") {
                                intervalsattime = `토요일&nbsp;&nbsp;${data.intervaltime}분<br>`;
                            }
                            if (typeof data.intervalsuntime != "undefined") {
                                intervalsuntime = `일요일&nbsp;&nbsp;${data.intervaltime}분<br>`;
                            }
                            $('.modal_body').html(
                                `<header style="background-color: white; height: 60px; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
                                    <a href="javascript:void(0)" onclick="closeModal()" style="color: #333; font-size: 24px; text-decoration: none; margin-right: 20px;">←</a>
                                    <div style="color: #4CAF50; font-size: 20px; font-weight: bold; margin: 0 auto;">${encoded.busnum}</div>
                                </header>

                                <div style="background-color: white;">
                                <div style="padding: 20px; border-bottom: 1px solid #eee;">
                                    <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
                                        <span style="margin-right: 5px;">🚌</span>
                                        운행지역
                                    </div>
                                    <div style="font-size: 16px; line-height: 1.5;">
                                        ${encoded.startstation} ↔ ${encoded.endstation}
                                    </div>
                                    <div style="color: #666; font-size: 14px; margin-top: 5px;">
                                        ${encoded.cityname}  ${encoded.bustype}
                                    </div>
                                </div>

                                <div style="padding: 20px; border-bottom: 1px solid #eee;">
                                    <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px; text-align: center;">
                                        <span style="margin-right: 5px;">🕔</span>
                                        운행시간
                                    </div>
                                    <div style="background: white; padding: 15px; border-radius: 8px;">
                                        <div style="display: flex; justify-content: center; margin-bottom: 8px;">
                                            <span>${startTime} ~ ${endTime}</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="padding: 20px; border-bottom: 1px solid #eee;">
                                    <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
                                        <span style="margin-right: 5px;">⌛</span>
                                        배차간격
                                    </div>
                                    <div style="font-size: 15px;">
                                        ${intervalsuntime}
                                        ${intervalsattime}
                                        ${intervaltime}
                                    </div>
                                </div>

                                <div style="padding: 20px;">
                                    <div style="color: #FF6B6B; font-size: 15px; margin-bottom: 10px;">
                                        <span style="margin-right: 5px;">🚏</span>
                                        주요경유지
                                    </div>
                                    <div style="font-size: 15px; line-height: 1.6;">
                                        ${station_name}
                                </div>
                            </div>`
                            );
                            modal.style.display="flex";
                        }
                    });
                    
                });
                $("#map").click(function(){
                    $.ajax({
                        type: 'get',
                        dataType: "json",
                        url: '/bus/ax_search_map',
                        data: { 
                            "routeid": $('input[name=routeid]').val(),
                            "routeno": $('input[name=routeno]').val(),
                            "citycode": $('input[name=citycode]').val()
                        },
                        success: function(data) {
                            $('.modal_map').html(`
                                <header style="background-color: white; height: 60px; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
                                    <a href="javascript:void(0)" onclick="javascript:modal.style.display='none'" style="color: #333; font-size: 24px; text-decoration: none; margin-right: 20px;">←</a>
                                    <div style="color: #4CAF50; font-size: 20px; font-weight: bold; margin: 0 auto;">${encoded.busnum} 노선도</div>
                                </header>
                                <div style="background-color: white; padding: 20px;">
                                    <div id="map_div" style="width: 100%; height: 600px; border-radius: 8px;"></div>
                                </div>
                            `);
                            
                            modal.style.display = "flex";

                            try {
                                const stations = data[0].stationdata;
                                
                                // 좌표 유효성 검사 및 변환
                                const validStations = stations.filter(station => {
                                    return station.gpslati && station.gpslong && 
                                        !isNaN(parseFloat(station.gpslati)) && 
                                        !isNaN(parseFloat(station.gpslong));
                                });

                                if (validStations.length === 0) {
                                    throw new Error('유효한 좌표가 없습니다.');
                                }

                                // 첫 번째 유효한 좌표로 지도 초기화
                                const firstStation = validStations[0];
                                const initialPosition = new naver.maps.LatLng(
                                    parseFloat(firstStation.gpslati),
                                    parseFloat(firstStation.gpslong)
                                );

                                const map = new naver.maps.Map('map_div', {
                                    center: initialPosition,
                                    zoom: 12
                                });

                                // 마커와 경로 좌표 배열
                                const markers = [];
                                const pathCoordinates = [];

                                // 각 정류장에 대한 마커 생성 및 경로 좌표 추가
                                validStations.forEach((station, index) => {
                                    const position = new naver.maps.LatLng(
                                        parseFloat(station.gpslati),
                                        parseFloat(station.gpslong)
                                    );
                                    pathCoordinates.push(position);

                                    // 마커 생성
                                    const marker = new naver.maps.Marker({
                                        position: position,
                                        map: map
                                    });
                                    markers.push(marker);

                                    // 정보창 생성
                                    const infoWindow = new naver.maps.InfoWindow({
                                        content: `
                                            <div style="padding: 10px;">
                                                <strong>${station.nodenm}</strong><br>
                                                정류장번호: ${station.nodeno}
                                            </div>
                                        `
                                    });

                                    // 마커 클릭 이벤트
                                    naver.maps.Event.addListener(marker, 'click', function() {
                                        infoWindow.getMap() ? infoWindow.close() : infoWindow.open(map, marker);
                                    });
                                });

                                // 경로선 그리기
                                if (pathCoordinates.length > 1) {
                                    new naver.maps.Polyline({
                                        path: pathCoordinates,
                                        strokeColor: '#5347AA',
                                        strokeWeight: 3,
                                        map: map
                                    });
                                }

                                // 모든 마커가 보이도록 지도 범위 조정
                                if (pathCoordinates.length > 0) {
                                    const bounds = new naver.maps.LatLngBounds();
                                    pathCoordinates.forEach(coord => bounds.extend(coord));
                                    map.fitBounds(bounds);
                                }

                            } catch (error) {
                                console.error('지도 초기화 오류:', error);
                                $('#map_div').html('지도를 불러오는 중 오류가 발생했습니다.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax 요청 실패:', error);
                            $('.modal_map').html('데이터를 불러오는데 실패했습니다.');
                        }
                    });
                });
    },
    error: function() {
    }, 
    complete: function(){
    }
    });
    });


</script>
</body>
</html>