<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pivot_User_Course extends Model
{
    //
    protected $table = "pivot_user_course";
    protected $fillable = [
        'sort', 'type', 'user_id', 'course_id', 'content_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // 话题
    function course()
    {
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    // 章节
    function chapter()
    {
        return $this->belongsTo('App\Models\Content','content_id','id');
    }





}
