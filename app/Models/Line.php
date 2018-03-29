<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    //
    protected $table = "lines";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'title', 'description', 'content', 'orderby', 'cover_pic',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';
//    protected $dates = ['created_at', 'updated_at', 'disabled_at'];

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

    // 内容
    function points()
    {
        return $this->hasMany('App\Models\Point','line_id','id');
    }

    // 评论
    function communications()
    {
        return $this->hasMany('App\Models\Communication','line_id','id');
    }

    // 评论
    function comments()
    {
        return $this->hasMany('App\Models\Communication','line_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\Pivot_User_Collection','line_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\Pivot_User_Item','line_id','id');
    }

    // 与我相关的话题
    function pivot_collection_line_users()
    {
        return $this->belongsToMany('App\User','pivot_user_collection','line_id','user_id');
    }

    // 与我相关的话题
    function pivot_collection_point_users()
    {
        return $this->belongsToMany('App\User','pivot_user_collection','line_id','user_id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
