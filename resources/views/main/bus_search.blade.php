<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>버스 검색</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .search-header {
            background: white;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .search-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            background: white;
        }
        
        .tab {
            flex: 1;
            text-align: center;
            padding: 15px;
            color: #666;
            text-decoration: none;
        }
        
        .tab.active {
            color: #FF4444;
            border-bottom: 2px solid #FF4444;
        }
        
        .search-input {
            width: 100%;
            padding: 12px;
            border: none;
            outline: none;
            font-size: 16px;
        }
        
        .back-button {
            color: #000;
            text-decoration: none;
            font-size: 20px;
            margin-right: 15px;
        }

        .tab:hover {
            text-decoration: none;
        }

         /* 기존 스타일에 추가 */
        .list-group-item {
            background: white;
            margin-bottom: 8px;
            border-radius: 8px !important;
            border: 1px solid #eee !important;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: normal;
            font-size: 0.8em;
        }

        #searchResults {
            margin-top: 120px; /* 헤더와 탭의 높이만큼 여백 */
            padding: 15px;
        }

        .no-results {
            margin-top: 120px;
        }
    </style>
</head>
<div class="search-header d-flex align-items-center">
    <a href="{{ url()->previous() }}" class="back-button">←</a>
    <input type="text" id="searchBox" class="search-input" placeholder="버스 검색" autofocus>
    <input type="hidden" name="mode" value="bus">
</div>

<script>
$(document).ready(function() {
    $('#searchBox').on('keyup', function() {
        var query = $(this).val();
        var mode = $('input[name=mode]').val();
        
        $.ajax({
            type: 'get',
            dataType: "json",
            url: '/bus/ax_search_keyword',
            data: { "keyword": query, "mode": mode },
            success: function(data) {
                let resultHtml = '<div class="list-group mt-3" id="loading">';
                
                data.forEach(function(item) {
                    if (item.bus && item.bus.length > 0) {
                        item.bus.forEach(function(bus) {
                            if (bus.routeno) { // 버스 노선 정보일 경우
                                resultHtml += `
                                    <a href="/station?citycode=${item.citycode}&cityname=${item.cityname}&routeno=${bus.routeno}" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">[${item.cityname}] ${bus.routeno}</h6>
                                                <small class="text-muted">
                                                    ${bus.startnodenm} → ${bus.endnodenm}
                                                </small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">${bus.routetp}</span>
                                        </div>
                                        <div class="mt-2 small text-muted">
                                            <span class="me-3">첫차: ${bus.startvehicletime || '-'}</span>
                                            <span>막차: ${bus.endvehicletime || '-'}</span>
                                        </div>
                                    </a>
                                `;
                            } else if (bus.nodeno) { // 정류장 정보일 경우
                                resultHtml += `
                                    <a href="/station_info?citycode=${item.citycode}&cityname=${item.cityname}&nodeNm=${item.bus[0].nodenm}" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">[${item.cityname}] ${bus.nodenm}</h6>
                                                <small class="text-muted">
                                                    ${bus.nodeno}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            }
                        });
                    }
                });
                resultHtml += '</div>';
                
                if (data.length > 0) {
                    $('#searchResults').html(resultHtml);
                    $('.no-results').hide();
                } else {
                    $('#searchResults').empty();
                    $('.no-results').show();
                    $('#noResultsText').text('검색 결과가 없습니다.');
                }
            },
            error: function() {
                $('#searchResults').empty();
                $('.no-results').show();
                $('#noResultsText').text('검색 중 오류가 발생했습니다.');
            }
        });
    });
});
</script>
    
    <div class="search-tabs">
        <a href="javascript:void(0)" class="tab active" onclick="changeTab('bus', this)">
            <span style="color: #FF4444;">🚌</span> 버스
        </a>
        <a href="javascript:void(0)" class="tab" onclick="changeTab('station', this)">
            <span>🚏</span> 정류장
        </a>
    </div>
    
    <div class="no-results text-center mt-5 text-secondary">
        <div style="margin-bottom: 10px;">!</div>
        <div id="noResultsText">버스 히스토리가 없습니다.</div>
    </div>

    <div id="searchResults" class="container mt-3"></div>

    <script>
        function changeTab(type, element) {
            // 모든 탭에서 active 클래스 제거
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // 클릭된 탭에 active 클래스 추가
            element.classList.add('active');
            
            // 검색창 플레이스홀더 변경
            const searchInput = document.getElementById('searchInput');
            const noResultsText = document.getElementById('noResultsText');
            $('input[name=mode]').val(type)
            if (type === 'bus') {
                searchBox.placeholder = '버스 검색';
                noResultsText.textContent = '버스 히스토리가 없습니다.';
                element.querySelector('span').style.color = '#FF4444';
                document.querySelectorAll('.tab')[1].querySelector('span').style.color = '#666';  
            } else {
                searchBox.placeholder = '정류장 검색';
                noResultsText.textContent = '정류장 히스토리가 없습니다.';
                element.querySelector('span').style.color = '#FF4444';
                document.querySelectorAll('.tab')[0].querySelector('span').style.color = '#666';
                $(function(){
                    $('#searchResults').empty();
                });
            }
        }
    </script>


</body>
</html>
