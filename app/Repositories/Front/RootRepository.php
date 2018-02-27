<?php
namespace App\Repositories\Front;

use App\User;
use App\Models\Course;
use App\Models\Content;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class RootRepository {

    private $model;
    public function __construct()
    {
    }


    // 平台主页
    public function view_courses($post_data)
    {
        $courses = Course::with([
            'user',
            'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); }
        ])->where('active', 1)->orderBy('id','desc')->paginate(20);
        return view('frontend.root.courses')->with(['datas'=>$courses]);
    }



    // 课程详情
    public function view_course($post_data,$id=0)
    {
//        $course_encode = $post_data['id'];
        $course_encode = $id;
        $course_decode = decode($course_encode);
        if(!$course_decode) return view('frontend.404');

        $content = [];
        if(!empty($post_data['content']))
        {
            $content_encode = $post_data['content'];
            $content_decode = decode($content_encode);
            if(!$content_decode) return view('frontend.404');

            $content = Content::find($content_decode);
            if(!$content)  return view('frontend.404');
        }

        $course = Course::with([
            'user',
            'contents'=>function($query) { $query->orderBy('id','asc'); }
        ])->where('id',$course_decode)->first();

        $course->encode_id = encode($course->id);
        $course->user->encode_id = encode($course->user->id);

        $course->contents_recursion = $this->get_recursion($course->contents,0);

        return view('frontend.course.course')->with(['data'=>$course,'content'=>$content]);
    }



    // 用户首页
    public function view_user($post_data,$id=0)
    {
//        $course_encode = $post_data['id'];
        $user_encode = $id;
        $user_decode = decode($user_encode);
        if(!$user_decode) return view('frontend.404');

        $user = User::with([
            'courses'=>function($query) { $query->orderBy('id','desc'); }
        ])->find($user_decode);

        $courses = Course::with([
            'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); }
        ])->where(['user_id'=>$user_decode,'active'=>1])->orderBy('id','desc')->paginate(20);

        return view('frontend.user.user')->with(['data'=>$user,'courses'=>$courses]);
    }



    // 顺序排列
    function get_recursion($result, $parent_id=0, $level=0)
    {
        /*记录排序后的类别数组*/
        static $list = array();

        foreach ($result as $k => $v)
        {
            if($v->p_id == $parent_id)
            {
                $v->level = $level;

                foreach($list as $key=>$val)
                {
                    if($val->id == $parent_id) $list[$key]->has_child = 1;
                }

                /*将该类别的数据放入list中*/
                $list[] = $v;

                $this->get_recursion($result, $v->id, $level+1);
            }
        }

        return $list;
    }




}