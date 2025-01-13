<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Station_s;

class StationController extends Controller
{
    
    private $station_s;

    public function __construct(station_s $station_s)
    {
        $this->station_s = $station_s;
    }

    //    
    /**
     * index
     * 버스의 노선에 있는 정류장 리스트 출력, 버스의 실시간 위치 보여주는 페이지
     * @return view
     */
    public function index(Request $request) 
    {
        return view("station/station", [
            'citycode' => $request->input('citycode') ,
            'cityname' => $request->input('cityname') ,
            'routeno' => $request->input('routeno') ,
            'buscity' => $request->input('buscity') 
        ]);
    }
    
    /**
     * info
     * 정류장의 정보 출력하는 페이지
     * @return view
     */
    public function info() 
    {
        return view("station/station_info");
    }
    
    /**
     * search
     * 버스번호나 정류장의 정보 검색하는 페이지
     * @return view
     */
    public function search()
    {
        return view('main.bus_search');
    }
    
    /**
     * ax_search
     *
     * @param  mixed $request
     * @return void
     */
    public function ax_search(Request $request) {
        $result=$this->station_s->search(array(
            "citycode" => $request->input('citycode'),
            "cityname" => $request->input('cityname'),
            "routeno" => $request->input('routeno'),
            "buscity" => $request->input('buscity')
        ));
        return $result;
    }

}
