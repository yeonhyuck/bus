<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorite';  // 테이블 이름
    protected $primaryKey = 'idx';  // 기본키 설정
    public $timestamps = false;     // timestamps 사용하지 않음

    protected $fillable = [
        'bus_num',
        'start_station', 
        'end_station',
        'bus_type',
        'citycode',
        'cityname',
        'routeid'
    ];
}
?>