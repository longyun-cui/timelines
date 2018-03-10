<?php
namespace App\Repositories\Front;

use App\User;
use App\Models\Course;
use App\Models\Content;
use App\Models\Pivot_User_Collection;
use App\Models\Pivot_User_Course;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class RootRepository {

    private $model;
    public function __construct()
    {
    }


    // 平台主页
    public function view_item_html($id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $data = Course::with([
                'user',
                'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->find($id);
        }
        else
        {
            $data = Course::with([
                'user',
                'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); }
            ])->find($id);
        }
        return view('frontend.component.course')->with(['course'=>$data])->__toString();
    }




    // 平台主页
    public function view_courses($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $datas = Course::with([
                'user',
                'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where('active', 1)->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $datas = Course::with([
                'user',
                'contents'=>function($query) { $query->where('p_id',0)->orderBy('id','asc'); }
            ])->where('active', 1)->orderBy('id','desc')->paginate(20);
        }
        return view('frontend.root.courses')->with(['datas'=>$datas]);
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

            $content->increment('visit_num');
        }

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $course = Course::with([
                'user',
                'contents'=>function($query) { $query->orderBy('id','asc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->find($course_decode);
        }
        else
        {
            $course = Course::with([
                'user',
                'contents'=>function($query) { $query->orderBy('id','asc'); }
            ])->find($course_decode);
        }

        $course->increment('visit_num');

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




    // 收藏
    public function item_collect_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'course_id.required' => '参数有误',
            'content_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'course_id' => 'required',
            'content_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $course_encode = $post_data['course_id'];
            $course_decode = decode($course_encode);
            if(!$course_decode) return response_error([],"参数有误，请重试！");

            $course = Course::find($course_decode);
            if($course)
            {
                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->pivot_collection_courses()->attach($course_decode,['type'=>1,'content_id'=>0,'created_at'=>$time,'updated_at'=>$time]);

                    $course->increment('collect_num');

                    $html['html'] = $this->view_item_html($course_decode);

                    DB::commit();
                    return response_success($html);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '添加失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");
        }
        else return response_error([],"请先登录！");

    }
    // 取消收藏
    public function item_collect_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'course_id.required' => '参数有误',
            'content_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'course_id' => 'required',
            'content_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $course_encode = $post_data['course_id'];
            $course_decode = decode($course_encode);
            if(!$course_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $course = Course::find($course_decode);
            if($course)
            {
                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $collections = Pivot_User_Collection::where(['type'=>1,'user_id'=>$user_id,'course_id'=>$course_decode,'content_id'=>0]);
                    $count = count($collections->get());
                    if($count)
                    {
                        $num = $collections->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        $course->decrement('collect_num');
                    }
                    $html['html'] = $this->view_item_html($course_decode);

                    DB::commit();
                    return response_success($html);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '操作失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");

        }
        else return response_error([],"请先登录！");

    }


    // 点赞
    public function item_favor_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'course_id.required' => '参数有误',
            'content_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'course_id' => 'required',
            'content_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $course_encode = $post_data['course_id'];
            $course_decode = decode($course_encode);
            if(!$course_decode) return response_error([],"参数有误，请重试！");

            $course = Course::find($course_decode);
            if($course)
            {
                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->pivot_item_courses()->attach($course_decode,['type'=>1,'content_id'=>0,'created_at'=>$time,'updated_at'=>$time]);

                    $course->increment('favor_num');

                    $html['html'] = $this->view_item_html($course_decode);

                    DB::commit();
                    return response_success($html);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '添加失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");
        }
        else return response_error([],"请先登录！");

    }
    // 取消点赞
    public function item_favor_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'course_id.required' => '参数有误',
            'content_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'course_id' => 'required',
            'content_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $course_encode = $post_data['course_id'];
            $course_decode = decode($course_encode);
            if(!$course_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $course = Course::find($course_decode);
            if($course)
            {
                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $favors = Pivot_User_Course::where(['type'=>1,'user_id'=>$user_id,'course_id'=>$course_decode,'content_id'=>0]);
                    $count = count($favors->get());
                    if($count)
                    {
                        $num = $favors->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        $course->decrement('favor_num');
                    }
                    $html['html'] = $this->view_item_html($course_decode);

                    DB::commit();
                    return response_success($html);
                }
                catch (Exception $e)
                {
                    DB::rollback();
//                    exit($e->getMessage());
//                    $msg = $e->getMessage();
                    $msg = '操作失败，请重试！';
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");

        }
        else return response_error([],"请先登录！");

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