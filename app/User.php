<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'moblie', 'email', 'nickname', 'truename', 'description', 'portrait_img', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';


    // 线
    function lines()
    {
        return $this->hasMany('App\Models\Line','user_id','id');
    }

    // 与我相关的【线】
    function pivot_collection_lines()
    {
        return $this->belongsToMany('App\Models\Line','pivot_user_collection','user_id','line_id');
    }

    // 与我相关的【点】
    function pivot_collection_points()
    {
        return $this->belongsToMany('App\Models\Point','pivot_user_collection','user_id','point_id');
    }

    // 与我相关的【线】
    function pivot_item_lines()
    {
        return $this->belongsToMany('App\Models\Line','pivot_user_item','user_id','line_id');
    }

    // 与我相关的【点】
    function pivot_item_points()
    {
        return $this->belongsToMany('App\Models\Point','pivot_user_item','user_id','point_id');
    }


}
