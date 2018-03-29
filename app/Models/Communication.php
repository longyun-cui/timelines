<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    //
    protected $table = "communications";
    protected $fillable = [
        'sort', 'type', 'active', 'support', 'user_id', 'line_id', 'point_id', 'is_anonymous', 'reply_id', 'dialog_id', 'order',
        'title', 'content',
        'is_shared', 'favor_num', 'comment_num'
    ];
    protected $dateFormat = 'U';


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

    //
    function point()
    {
        return $this->belongsTo('App\Models\Point','point_id','id');
    }

    // 父节点
    function reply()
    {
        return $this->belongsTo('App\Models\Communication','reply_id','id');
    }

    // 子节点
    function children()
    {
        return $this->hasMany('App\Models\Communication','reply_id','id');
    }

    // 对话
    function dialogs()
    {
        return $this->hasMany('App\Models\Communication','dialog_id','id');
    }

    // 点赞
    function favors()
    {
        return $this->hasMany('App\Models\Communication','reply_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
