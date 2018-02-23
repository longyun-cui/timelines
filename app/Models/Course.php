<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = "courses";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'title', 'description', 'content', 'pic_cover',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 内容
    function contents()
    {
        return $this->hasMany('App\Models\Content','course_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
