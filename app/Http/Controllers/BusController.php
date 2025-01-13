<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Bus_s;
/**
 * BusController
 */
class BusController extends Controller
{

    private $bus_s;
    
    /**
     * __construct
     *
     * @param  mixed $bus_s
     * @return void
     */
    public function __construct(bus_s $bus_s)
    {
        $this->bus_s = $bus_s;
    }

    //    
    /**
     * index
     * 
     * @return void
     */
    public function index() 
    {
        return view("main/info");
    }
    
    /**
     * ax_search_keyword
     *
     * @param  mixed $request
     * @return void
     */
    public function ax_search_keyword(Request $request)
    {
        $dto_params = array("keyword" => $request->input('keyword'),
                            "mode" => $request->input('mode')
                        );
        $rlt = $this->bus_s->search_keyword($dto_params);
        
        return response()->json($rlt);
    }

    
    /**
     * ax_search_info
     *
     * @param  mixed $request
     * @return void
     */
    public function ax_search_info(Request $request) {
        $result = $this->bus_s->search_info(array(
            "citycode" => $request->input('citycode'),
            "routeid" => $request->input('routeid'),
            "endstation" => $request->input('endstation')
        ));
        return $result;
    }
    
    
    /**
     * ax_search_map
     *
     * @param  mixed $request
     * @return void
     */
    public function ax_search_map(Request $request) {
        $result = $this->bus_s->search_map(array(
            "citycode" => $request->input('citycode'),
            "routeid" => $request->input('routeid'),
            "routeno" => $request->input('routeno')
        ));
        return $result;
    }
}
