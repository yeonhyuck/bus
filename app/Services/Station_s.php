<?php

namespace App\Services;

/**
 * Class Station_s
 * @package App\Services
 */
class Station_s
{
    public function __construct()
    {

    }

    private $servicekey = "z77ZBdChsMxxR8HY78hs5hmHVwO0wZwa7S2NGyV4EfrS2vRy%2BIvbtySGeg%2BpNzXQjz6eGlqNYOXRZj%2F6HrlMkw%3D%3D";

    function search($dto_params) {
        // print_r($dto_params);exit;
        // 노선 정보
        $routeNoUrl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService/getRouteNoList?serviceKey=" . $this->servicekey. "&cityCode=" .$dto_params['citycode']. "&routeNo=" .$dto_params['routeno']. "&numOfRows=1000&pageNo=1&_type=json";
        $routeData = curl($routeNoUrl);
        // print_r($routeData);exit;
        $station = array();
        if (isset($routeData['response']) 
            && isset($routeData['response']['header'])
            && isset($routeData['response']['header']['resultCode']) 
            && $routeData['response']['header']['resultCode'] == "00") 
        {
            $data_list = $routeData['response']['body']['items']['item'];
            // print_r($data_list);exit;
            foreach ($data_list as $key => $item) {
                if ($item['routeno'] == $dto_params['routeno']) {
                    // 경유 정류소 목록
                    // echo "gd";exit;
                    $stationUrl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService/getRouteAcctoThrghSttnList?serviceKey=" . $this->servicekey. "&cityCode=" .$dto_params['citycode']. "&routeId=". $item['routeid']. "&numOfRows=10000&pageNo=1&_type=json";
                    $stationData = curl($stationUrl);

                    if (isset($stationData['response']) 
                        && isset($stationData['response']['header'])
                        && isset($stationData['response']['header']['resultCode']) 
                        && $stationData['response']['header']['resultCode'] == "00") 
                    {
                        foreach ($stationData['response']['body']['items']['item'] as $key => $stop) {
                            if(isset($stop['nodenm']) && isset($stop['nodeno'])) {
                                $station[$stop['nodenm']] = array(
                                    'num' => $stop['nodeno']
                                );
                            }
                        }
                    }
                    //버스 위치 정보
                    $nowurl = "http://apis.data.go.kr/1613000/BusLcInfoInqireService/getRouteAcctoBusLcList?serviceKey=" . $this->servicekey. "&cityCode=" .$dto_params['citycode']. "&routeId=" . $item['routeid'] ."&numOfRows=1000&pageNo=1&_type=json";
                    $nowdata = curl($nowurl);

                    // print_r($nowdata);exit;

                    $now = array();
                    if (isset($nowdata['response']) 
                        && isset($nowdata['response']['header'])
                        && isset($nowdata['response']['header']['resultCode']) 
                        && $nowdata['response']['header']['resultCode'] == "00") 
                    {
                        // var_dump($nowdata);exit;
                        if (is_array($nowdata['response']['body']['items'])) {
                            foreach($nowdata['response']['body']['items']['item'] as $lot) {
                                if (isset($station[$lot['nodenm']])) {
                                    $station[$lot['nodenm']]['location'] = $lot;
                                }
                            }
                        }
                        
                    }
                }

                

                return array(
                    'encoded'=> array(
                        'citycode' => $dto_params['citycode'], 
                        'cityname' => $dto_params['cityname'],
                        'bustype' => $item['routetp'],
                        'busnum' => $item['routeno'],
                        'end' => $item['endvehicletime'],
                        'start' => $item['startvehicletime'],
                        'startstation' =>  $item['startnodenm'],
                        'endstation' =>  $item['endnodenm'],
                        'routeid' => $item['routeid']
    
                    ),
                    'encoded2' => $station,
                    'now' => $now
                );
                
            }
            
        }
        else {
            return array();
        }
    }
}
