<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pivot_User_Item extends Model
{
    //
    protected $table = "pivot_user_item";
    protected $fillable = [
        'sort', 'type', 'user_id', 'line_id', 'point_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 话题
    function line()
    {
        return $this->belongsTo('App\Models\Line','line_id','id');
    }

    // 章节
    function point()
    {
        return $this->belongsTo('App\Models\Point','point_id','id');
    }





}
