<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\MainService;

class MainController extends Controller
{
    private $MainService;

    public function __construct(MainService $MainService)
    {
        $this->MainService = $MainService;
    }
    //
    /**
     * index
     *
     * @return void
     */
    public function index() 
    {
        return view("main/index");
    }
    
    /**
     * ax_search
     *
     * @param  mixed $request
     * @return void
     */
    public function ax_search(Request $request) {

        /**
         * 
         * $request = array(key : value)
         * 사용자가 검색한 노선번호를 받아서.. 서비스에 전달하고.. 서비스에서 API 호출 및 가공된 데이터를 다시 전달받아
         * VIEW 에 전달합니다.
         * VIEW(사용자) -> controlls (ax_search) -> service(api 호출 가공) -> controlls -> view
         */
        $this->MainService->search($request);
    }


}
