<?php

namespace App\Services;



/**
 * Class Bus_s
 * @package App\Services
 */
class Bus_s
{
    //api url 접근하는 서비스키
    private $servicekey = "";

    //api 공통 url
    private $apiurl = "http://apis.data.go.kr/1613000/BusRouteInfoInqireService";

    /**
     * search_keyword
     * keyword랑 mode 받아와서 버스 정보를 return함
     * @param  mixed $dto_params keyword랑 mode가 담겨져있음
     * @return array
     */
    public function search_keyword(array $dto_params) : array
    {
        //mode가 버스일 경우
        if ($dto_params['mode'] == 'bus') {
            
            //whatnum 변수에 keyword 담기
            $whatnum= $dto_params['keyword'];
            // 도시 코드 찾기
            $cityCodeUrl = $this->apiurl."/getCtyCodeList?serviceKey=".$this->servicekey."&_type=json";
            $cityData = curl($cityCodeUrl);

            $citycode = array();

            if (isset($cityData['response']['body']['items']['item'])) {
                $citycode = $cityData['response']['body']['items']['item'];
            }

            //citycode 루프돌면서 버스가 있으면 bus 배열에 버스 정보 담기
            foreach($citycode as &$value) {
                $city = $value['citycode'];
                $routeNoUrl = $this->apiurl."/getRouteNoList?serviceKey=".$this->servicekey."&cityCode=".$city."&routeNo=".$whatnum."&numOfRows=5&pageNo=1&_type=json";
                $routeData = curl($routeNoUrl);
                $value['bus'] = array();
                if (is_array($routeData['response']['body']['items']) && isset($routeData['response']['body']['items']['item'])) {
                    $value['bus'] = $routeData['response']['body']['items']['item'];
                }
            }


        //mode가 station일 경우
        } else if ($dto_params['mode'] == 'station') {
            $whatname = urlencode($dto_params['keyword']);
            $cityCodeUrl = $this->apiurl."/getCtyCodeList?serviceKey=".$this->servicekey."&_type=json";
            $cityData = curl($cityCodeUrl);
            $citycode = $cityData['response']['body']['items']['item'];

            //citycode 루프돌면서 버스가 있으면 bus 배열에 버스 정보 담기
            foreach($citycode as &$value) {
                $city = $value['citycode'];
                $stationUrl = $this->apiurl."/getSttnNoList?serviceKey=".$this->servicekey."&cityCode=".$city."&nodeNm=".$whatname."&numOfRows=10&pageNo=1&_type=json";
                $stationData = curl($stationUrl);
                $value['bus'] = array();
                if (is_array($stationData['response']['body']['items']) && isset($stationData['response']['body']['items']['item'])) {
                    $value['bus'] = $stationData['response']['body']['items']['item'];
                }
            }
        }

        return $citycode;
    }


    /**
     * search_info
     * 버스 기본 정보 찾아서 return
     * @param  mixed $dto_params citycode routeid endstation 담겨져있는 배열
     * @return void
     */
    function search_info($dto_params) {

        //정류장 세부 정보 찾는 url
        $stationUrl = $this->apiurl."/getRouteInfoIem?serviceKey=".$this->servicekey."&cityCode=". $dto_params['citycode']. "&routeId=". $dto_params['routeid']. "&_type=json";
        $stationData = curl($stationUrl);

        return $stationData['response']['body']['items']['item'];
    }
    
    /**
     * search_map
     * 지도 api를 사용하기 위한 변수 찾기
     * @param  mixed $dto_params2 citycode routeid routeno 담겨져있는 배열열
     * @return void
     */
    function search_map($dto_params2) {

        //정류장 정보
        $stationUrl = $this->apiurl."/getRouteAcctoThrghSttnList?serviceKey=".$this->servicekey."&cityCode=". $dto_params2['citycode']. "&routeId=". $dto_params2['routeid']. "&numOfRows=10000&pageNo=1&_type=json";
        $stationData = curl($stationUrl);

        //경로 정보
        $routeNoUrl = $this->apiurl."/getRouteNoList?serviceKey=".$this->servicekey."&cityCode=". $dto_params2['citycode']. "&routeNo=". $dto_params2['routeno']. "&numOfRows=1000&pageNo=1&_type=json";
        $routeData = curl($routeNoUrl);

        //result 배열에 시작 정류장 위치, 마지막 정류장 위치, 정류장 정보 담기
        $result = array(
            'endnodenm' => $routeData['response']['body']['items']['item'][0]['endnodenm'],
            'startnodenm' => $routeData['response']['body']['items']['item'][0]['startnodenm'],
            array(
                'stationdata' => $stationData['response']['body']['items']['item']
        ));

        return $result;
    }
}

