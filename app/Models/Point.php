<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //
    protected $table = "points";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'line_id', 'order', 'title', 'description', 'content',
        'time', 'start_time', 'end_time',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at', 'disabled_at'];

    public function getDates()
    {
        return array('created_at','updated_at');
//        return array(); // 原形返回；
    }


    // 管理员
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 课程
    function line()
    {
        return $this->belongsTo('App\Models\Line','line_id','id');
    }

    // 评论
    function communications()
    {
        return $this->hasMany('App\Models\Communication','point_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\Pivot_User_Collection','point_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\Pivot_User_Item','point_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
