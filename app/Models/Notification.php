<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = "notifications";
    protected $fillable = [
        'sort', 'type', 'is_read', 'user_id', 'source_id', 'course_id', 'content_id', 'comment_id', 'reply_id', 'content', 'ps'
    ];
    protected $dateFormat = 'U';

    function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    function source()
    {
        return $this->belongsTo('App\User','source_id','id');
    }

    function course()
    {
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    function chapter()
    {
        return $this->belongsTo('App\Models\Content','content_id','id');
    }

    function comment()
    {
        return $this->belongsTo('App\Models\Communication','comment_id','id');
    }

    function reply()
    {
        return $this->belongsTo('App\Models\Communication','reply_id','id');
    }


}
