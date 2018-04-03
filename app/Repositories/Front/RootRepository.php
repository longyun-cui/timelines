<?php
namespace App\Repositories\Front;

use App\User;
use App\Models\Line;
use App\Models\Point;
use App\Models\Communication;
use App\Models\Notification;
use App\Models\Pivot_User_Collection;
use App\Models\Pivot_User_Item;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Blade;
use QrCode;

class RootRepository {

    private $model;
    public function __construct()
    {
        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }


    // 课程模板
    public function view_item_html($id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $line = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->find($id);
        }
        else
        {
            $line = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); }
            ])->find($id);
        }
        $lines[0] = $line;
        return view('frontend.component.line')->with(['lines'=>$lines])->__toString();
    }


    // 平台主页
    public function view_lines($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $lines = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id,'point_id' => 0]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id,'point_id' => 0]); }
            ])->where('active', 1)->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $lines = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); }
            ])->where('active', 1)->orderBy('id','desc')->paginate(20);
        }

        foreach ($lines as $item)
        {
            $item->content_show = strip_tags($item->content);
            $img_tags = get_html_img($item->content);
            $item->img_tags = $img_tags;
        }
//        dd($lines->toArray());

        return view('frontend.root.lines')->with(['line_magnitude'=>'item-plural','lines'=>$lines]);
    }


    // 用户首页
    public function view_user($post_data,$id=0)
    {
//        $line_encode = $post_data['id'];
        $user_encode = $id;
        $user_decode = decode($user_encode);
        if(!$user_decode) return view('frontend.404');

        $user = User::with([
            'lines'=>function($query) { $query->orderBy('id','desc'); }
        ])->withCount('lines')->find($user_decode);
        $user->timestamps = false;
        $user->increment('visit_num');

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $lines = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); },
                'collections'=>function($query) use ($me_id) { $query->where(['user_id' => $me_id,'point_id' => 0]); },
                'others'=>function($query) use ($me_id) { $query->where(['user_id' => $me_id,'point_id' => 0]); }
            ])->where(['user_id'=>$user_decode,'active'=>1])->orderBy('id','desc')->paginate(20);
        }
        else
        {
            $lines = Line::with([
                'user',
                'points'=>function($query) { $query->where(['active'=>1])->orderBy('id','desc'); }
            ])->where(['user_id'=>$user_decode,'active'=>1])->orderBy('id','desc')->paginate(20);
        }

        foreach ($lines as $item)
        {
            $item->content_show = strip_tags($item->content);
            $img_tags = get_html_img($item->content);
            $item->img_tags = $img_tags;
        }
//        dd($lines->toArray());

        return view('frontend.root.user')->with(['line_magnitude'=>'item-plural','data'=>$user,'lines'=>$lines]);
    }


    // 课程详情
    public function view_line($post_data,$id=0)
    {
//        $line_encode = $post_data['id'];
        $line_encode = $id;
        $line_decode = decode($line_encode);
        if(!$line_decode) return view('frontend.404');


        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $line = Line::with([
                'user',
                'points'=>function($query) { $query->with(['user'])->where(['active'=>1])->orderBy('time','desc'); },
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id,'point_id' => 0]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id,'point_id' => 0]); }
            ])->find($line_decode);

            if($line->orderby == 1) $orderby = 'asc';
            else $orderby = 'desc';

            $query = Point::with([
                'user',
                'collections'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id) { $query->where(['user_id' => $user_id]); }
            ])->where('line_id',$line_decode);

            $query->orderByRaw(DB::raw('cast(time as SIGNED) '.$orderby));
            $query->orderByRaw(DB::raw('cast(time as decimal) '.$orderby));
            $query->orderBy('time',$orderby);
            $points = $query->get();
        }
        else
        {
            $line = Line::with([
                'user',
                'points'=>function($query) { $query->with(['user'])->where(['active'=>1])->orderBy('time','desc'); }
            ])->find($line_decode);

            if($line->orderby == 1) $orderby = 'asc';
            else $orderby = 'desc';

            $query = Point::with(['user'])->where('line_id',$line_decode);

            $query->orderByRaw(DB::raw('cast(time as SIGNED) '.$orderby));
            $query->orderByRaw(DB::raw('cast(time as decimal) '.$orderby));
            $query->orderBy('time',$orderby);
            $points = $query->get();
        }

        $line->comments_total = $line->comment_num + $line->points->sum('comment_num');

        $line->content_show = $line->content;
        $img_tags = get_html_img($line->content);
        $line->img_tags = $img_tags;

        $line->timestamps = false;
        $line->increment('visit_num');

        foreach ($points as $item)
        {
            $item->content_show = strip_tags($item->content);
            $img_tags = get_html_img($item->content);
            $item->img_tags = $img_tags;
        }

        $author = User::find($line->user_id);
        $author->timestamps = false;
        $author->increment('visit_num');

        $line->encode_id = encode($line->id);
        $line->user->encode_id = encode($line->user->id);
        $lines[0] = $line;

        return view('frontend.root.line')
            ->with(['line_magnitude'=>'item-singular','point_magnitude'=>'item-plural','line'=>$line,'lines'=>$lines,'points'=>$points]);
    }


    // 课程详情
    public function view_point($post_data,$id=0)
    {
//        $point_encode = $post_data['id'];
        $point_encode = $id;
        $point_decode = decode($point_encode);
        if(!$point_decode) return view('frontend.404');


        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $point = Point::with([
                'user',
                'line',
                'collections'=>function($query) use ($user_id,$point_decode) { $query->where(['user_id' => $user_id]); },
                'others'=>function($query) use ($user_id,$point_decode) { $query->where(['user_id' => $user_id]); }
            ])->find($point_decode);
        }
        else
        {
            $point = Point::with([
                'user',
                'line'
            ])->find($point_decode);
        }

        $point->timestamps = false;
        $point->increment('visit_num');

        $author = User::find($point->user_id);
        $author->timestamps = false;
        $author->increment('visit_num');

        $point->encode_id = encode($point->id);
        $point->user->encode_id = encode($point->user->id);
        $points[0] = $point;

        return view('frontend.root.point')->with(['point_magnitude'=>'item-singular','point'=>$point]);
    }




    // 收藏
    public function item_collect_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"参数有误，请重试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $line = Line::find($line_decode);
            if($line)
            {
                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");
                }

                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->pivot_collection_lines()->attach($line_decode,['type'=>1,'point_id'=>$point_decode,'created_at'=>$time,'updated_at'=>$time]);

                    if($point_decode == 0)
                    {
                        $line->timestamps = false;
                        $line->increment('collect_num');
                    }
                    else
                    {
                        $point->timestamps = false;
                        $point->increment('collect_num');
                    }

                    $insert['type'] = 11;
                    $insert['user_id'] = $user->id;
                    $insert['line_id'] = $line_decode;
                    $insert['point_id'] = $point_decode;

                    $communication = new Communication;
                    $bool = $communication->fill($insert)->save();
                    if(!$bool) throw new Exception("insert--communication--fail");

                    DB::commit();
                    return response_success();
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
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $line = Line::find($line_decode);
            if($line)
            {
                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");
                }

                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $collections = Pivot_User_Collection::where(['type'=>1,'user_id'=>$user_id,'line_id'=>$line_decode,'point_id'=>$point_decode]);
                    $count = count($collections->get());
                    if($count)
                    {
                        $num = $collections->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        if($point_decode == 0) $line->decrement('collect_num');
                        else $point->decrement('collect_num');
                    }

                    $insert['type'] = 12;
                    $insert['user_id'] = $user->id;
                    $insert['line_id'] = $line_decode;
                    $insert['point_id'] = $point_decode;

                    $communication = new Communication;
                    $bool = $communication->fill($insert)->save();
                    if(!$bool) throw new Exception("insert--communication--fail");

                    DB::commit();
                    return response_success();
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
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"参数有误，请重试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $line = Line::find($line_decode);
            if($line)
            {
                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");
                }

                DB::beginTransaction();
                try
                {
                    $time = time();
                    $user = Auth::user();
                    $user->pivot_item_lines()->attach($line_decode,['type'=>1,'point_id'=>$point_decode,'created_at'=>$time,'updated_at'=>$time]);

                    if($point_decode == 0)
                    {
                        $line->timestamps = false;
                        $line->increment('favor_num');
                    }
                    else
                    {
                        $point->timestamps = false;
                        $point->increment('favor_num');
                    }

                    $insert['type'] = 3;
                    $insert['user_id'] = $user->id;
                    $insert['line_id'] = $line_decode;
                    $insert['point_id'] = $point_decode;

                    $communication = new Communication;
                    $bool = $communication->fill($insert)->save();
                    if(!$bool) throw new Exception("insert--communication--fail");

//                    通知对方
                    if($line->user_id != $user->id)
                    {
                        $notification_insert['type'] = 8;
                        $notification_insert['sort'] = 3;
                        $notification_insert['user_id'] = $line->user_id;
                        $notification_insert['source_id'] = $user->id;
                        $notification_insert['line_id'] = $line_decode;
                        $notification_insert['point_id'] = $point_decode;
                        $notification_insert['comment_id'] = $communication->id;

                        $notification = new Notification;
                        $bool = $notification->fill($notification_insert)->save();
                        if(!$bool) throw new Exception("insert--notification--fail");
                    }

                    DB::commit();
                    return response_success();
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    $msg = '添加失败，请重试！';
//                    $msg = $e->getMessage();
//                    exit($e->getMessage());
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
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $line = Line::find($line_decode);
            if($line)
            {
                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");
                }

                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $favors = Pivot_User_Item::where(['type'=>1,'user_id'=>$user_id,'line_id'=>$line_decode,'point_id'=>$point_decode]);
                    $count = count($favors->get());
                    if($count)
                    {
                        $num = $favors->delete();
                        if($num != $count) throw new Exception("delete--pivot--fail");

                        if($point_decode == 0) $line->decrement('favor_num');
                        else $point->decrement('favor_num');
                    }

                    $insert['type'] = 4;
                    $insert['user_id'] = $user->id;
                    $insert['line_id'] = $line_decode;
                    $insert['point_id'] = $point_decode;

                    $communication = new Communication;
                    $bool = $communication->fill($insert)->save();
                    if(!$bool) throw new Exception("insert--communication--fail");

                    DB::commit();
                    return response_success();
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    $msg = '操作失败，请重试！';
                    $msg = $e->getMessage();
//                    exit($e->getMessage());
                    return response_fail([], $msg);
                }
            }
            else return response_error([],"该话题不存在，刷新一下试试！");

        }
        else return response_error([],"请先登录！");

    }




    // 添加评论
    public function item_comment_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
            'content.required' => '内容不能为空',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required',
            'content' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"参数有误，刷新一下试试");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $user = Auth::user();
            $insert['type'] = $post_data['type'];
            $insert['user_id'] = $user->id;
            $insert['line_id'] = $line_decode;
            $insert['point_id'] = $point_decode;
            $insert['content'] = $post_data['content'];

            DB::beginTransaction();
            try
            {
                $line = Line::find($line_decode);
                if(!$line) return response_error([],"该课题不存在，刷新一下试试");

                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");

                    $point->timestamps = false;
                    $point->increment('comment_num');
                }
                else
                {
                    $line->timestamps = false;
                    $line->increment('comment_num');
                }

                $communication = new Communication;
                $bool = $communication->fill($insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

//                通知对方
                if($line->user_id != $user->id)
                {
                    $notification_insert['type'] = 8;
                    $notification_insert['sort'] = 1;
                    $notification_insert['user_id'] = $line->user_id;
                    $notification_insert['source_id'] = $user->id;
                    $notification_insert['line_id'] = $line_decode;
                    $notification_insert['point_id'] = $point_decode;
                    $notification_insert['comment_id'] = $communication->id;

                    $notification = new Notification;
                    $bool = $notification->fill($notification_insert)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $communications[0] = $communication;
                $html["html"] = view('frontend.component.comment')->with("communications",$communications)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
//                exit($e->getMessage());
//                $msg = $e->getMessage();
                $msg = '添加失败，请重试！';
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 获取评论
    public function item_comment_get($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $type = $post_data['type'];

        $line_encode = $post_data['line_id'];
        $line_decode = decode($line_encode);
        if(!$line_decode) return response_error([],"参数有误，刷新一下试试");

        $point_encode = $post_data['point_id'];
        $point_decode = decode($point_encode);
        if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
//                'dialogs'=>function($query) use ($user_id) { $query->with([
//                    'user',
//                    'reply'=>function($query1) { $query1->with(['user']); },
//                    'favors'=>function($query) use ($user_id)  { $query->where(['type'=>5,'user_id'=>$user_id]); }
//                ])->orderBy('id','desc'); },
                'favors'=>function($query) use ($user_id) { $query->where(['type'=>5,'user_id'=>$user_id]); }
            ])->withCount('dialogs')
            ->where(['type'=>$type,'reply_id'=>0,'line_id'=>$line_decode,'point_id'=>$point_decode]);
        }
        else
        {
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); }//,
//                'dialogs'=>function($query) { $query->with([
//                    'user',
//                    'reply'=>function($query1) { $query1->with(['user']); }
//                ])->orderBy('id','desc'); },
            ])->withCount('dialogs')
            ->where(['type'=>$type,'reply_id'=>0,'line_id'=>$line_decode,'point_id'=>$point_decode]);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        $comments = $comments->orderBy('id','desc')->paginate(10);

        foreach ($comments as $comment)
        {
            if($comment->dialogs_count)
            {
                $comment->dialog_max_id = 0;
                $comment->dialog_min_id = 0;
                $comment->dialog_more = 'more';
                $comment->dialog_more_text = '还有 <span class="text-blue">'.$comment->dialogs_count.'</span> 回复';
            }
            else
            {
                $comment->dialog_max_id = 0;
                $comment->dialog_min_id = 0;
                $comment->dialog_more = 'none';
                $comment->dialog_more_text = '没有了';
            }

//            if(count($comment->dialogs))
//            {
//                $comment->dialogs = $comment->dialogs->take(1);
//
//                $comment->dialog_max_id = $comment->dialogs->first()->id;
//                $comment->dialog_min_id = $comment->dialogs->last()->id;
//                if($comment->dialogs->count() >= 1)
//                {
//                    $comment->dialog_more = 'more';
//                    $comment->dialog_more_text = '更多';
//                }
//                else
//                {
//                    $comment->dialog_more = 'none';
//                    $comment->dialog_more_text = '没有了';
//                }
//            }
//            else
//            {
//                $comment->dialog_max_id = 0;
//                $comment->dialog_min_id = 0;
//                $comment->dialog_more = 'none';
//                $comment->dialog_more_text = '没有了';
//            }
        }

        if(!$comments->isEmpty())
        {
            $return["html"] = view('frontend.component.comment')->with("communications",$comments)->__toString();
            $return["max_id"] = $comments->first()->id;
            $return["min_id"] = $comments->last()->id;
            $return["more"] = ($comments->count() >= 10) ? 'more' : 'none';
        }
        else
        {
            $return["html"] = '';
            $return["max_id"] = 0;
            $return["min_id"] = 0;
            $return["more"] = 'none';
        }

        return response_success($return);

    }
    // 用户评论
    public function item_comment_get_html($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $line_encode = $post_data['line_id'];
        $line_decode = decode($line_encode);
        if(!$line_decode) return response_error([],"参数有误，刷新一下试试");

        $point_encode = $post_data['point_id'];
        $point_decode = decode($point_encode);
        if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

        $communications = Communication::with(['user'])
            ->where(['line_id'=>$line_decode,'point_id'=>$point_decode])->orderBy('id','desc')->get();

        $html["html"] = view('frontend.component.comments')->with("communications",$communications)->__toString();
        return response_success($html);

    }


    // 添加回复
    public function item_reply_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
            'content.required' => '回复不能为空',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required',
            'comment_id' => 'required',
            'content' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

            $user = Auth::user();
            $insert['type'] = $post_data['type'];
            $insert['user_id'] = $user->id;
            $insert['line_id'] = $line_decode;
            $insert['point_id'] = $point_decode;
            $insert['reply_id'] = $comment_decode;
            $insert['content'] = $post_data['content'];

            DB::beginTransaction();
            try
            {
                $line = Line::find($line_decode);
                if(!$line) return response_error([],"该课题不存在，刷新一下试试");

                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");

                    $point->timestamps = false;
                    $point->increment('comment_num');
                }
                else
                {
                    $line->timestamps = false;
                    $line->increment('comment_num');
                }

                $comment = Communication::find($comment_decode);
                if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");
                $comment->timestamps = false;
                $comment->increment('comment_num');

                if($comment->dialog_id)
                {
                    $insert['dialog_id'] = $comment->dialog_id;
                    $dialog = Communication::find($insert['dialog_id']);
                    $dialog->timestamps = false;
                    $dialog->increment('comment_num');
                }
                else
                {
                    $insert['dialog_id'] = $comment_decode;
                }

                $communication = new Communication;
                $bool = $communication->fill($insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

//                通知对方
                if($comment->user_id != $user->id)
                {
                    $notification_insert['type'] = 8;
                    $notification_insert['sort'] = 2;
                    $notification_insert['user_id'] = $comment->user_id;
                    $notification_insert['source_id'] = $user->id;
                    $notification_insert['line_id'] = $line_decode;
                    $notification_insert['point_id'] = $point_decode;
                    $notification_insert['comment_id'] = $communication->id;
                    $notification_insert['reply_id'] = $comment->id;

                    $notification = new Notification;
                    $bool = $notification->fill($notification_insert)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $communications[0] = $communication;
                $html["html"] = view('frontend.component.reply')->with("communications",$communications)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
//                exit($e->getMessage());
//                $msg = $e->getMessage();
                $msg = '添加失败，请重试！';
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 获取回复
    public function item_reply_get($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $type = $post_data['type'];

        $line_encode = $post_data['line_id'];
        $line_decode = decode($line_encode);
        if(!$line_decode) return response_error([],"参数有误，刷新一下试试");

        $point_encode = $post_data['point_id'];
        $point_decode = decode($point_encode);
        if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

        $comment_encode = $post_data['comment_id'];
        $comment_decode = decode($comment_encode);
        if(!$comment_decode) return response_error([],"参数有误，刷新一下试试");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
                'favors'=>function($query) use ($user_id) { $query->where(['type'=>5,'user_id'=>$user_id]); }
            ])->where(['type'=>$type,'line_id'=>$line_decode,'point_id'=>$point_decode,'dialog_id'=>$comment_decode])
                ->where('reply_id','<>',0);
        }
        else
        {
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
            ])->where(['type'=>$type,'line_id'=>$line_decode,'point_id'=>$point_decode,'dialog_id'=>$comment_decode])
                ->where('reply_id','<>',0);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        $comments = $comments->orderBy('id','desc')->paginate(10);

        if(!$comments->isEmpty())
        {
            $return["html"] = view('frontend.component.reply')->with("communications",$comments)->__toString();
            $return["max_id"] = $comments->first()->id;
            $return["min_id"] = $comments->last()->id;
            $return["more"] = ($comments->count() >= 10) ? 'more' : 'none';
        }
        else
        {
            $return["html"] = '';
            $return["max_id"] = 0;
            $return["min_id"] = 0;
            $return["more"] = 'none';
        }

        return response_success($return);

    }


    // 评论点赞
    public function item_comment_favor_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

            $user = Auth::user();
            $insert['type'] = $post_data['type'];
            $insert['user_id'] = $user->id;
            $insert['line_id'] = $line_decode;
            $insert['point_id'] = $point_decode;
            $insert['reply_id'] = $comment_decode;

            DB::beginTransaction();
            try
            {
                $line = Line::find($line_decode);
                if(!$line) return response_error([],"该课题不存在，刷新一下试试");

                if($point_decode != 0)
                {
                    $point = Point::find($point_decode);
                    if(!$line && $point->line_id != $line_decode) return response_error([],"参数有误，刷新一下试试");

                    $point->timestamps = false;
                    $point->increment('favor_num');
                }
                else
                {
                    $line->timestamps = false;
                    $line->increment('favor_num');
                }

                $comment = Communication::find($comment_decode);
                if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");
                $comment->timestamps = false;
                $comment->increment('favor_num');

                $communication = new Communication;
                $bool = $communication->fill($insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

//                通知对方
                if($comment->user_id != $user->id)
                {
                    $notification_insert['type'] = 8;
                    $notification_insert['sort'] = 5;
                    $notification_insert['user_id'] = $comment->user_id;
                    $notification_insert['source_id'] = $user->id;
                    $notification_insert['line_id'] = $line_decode;
                    $notification_insert['point_id'] = $point_decode;
                    $notification_insert['comment_id'] = $communication->id;
                    $notification_insert['reply_id'] = $comment_decode;

                    $notification = new Notification;
                    $bool = $notification->fill($notification_insert)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                DB::commit();
                return response_success();
            }
            catch (Exception $e)
            {
                DB::rollback();
//                exit($e->getMessage());
//                $msg = $e->getMessage();
                $msg = '添加失败，请重试！';
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 评论取消赞
    public function item_comment_favor_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'line_id.required' => '参数有误',
            'point_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'line_id' => 'required',
            'point_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $line_encode = $post_data['line_id'];
            $line_decode = decode($line_encode);
            if(!$line_decode) return response_error([],"该话题不存在，刷新一下试试！");

            $point_encode = $post_data['point_id'];
            $point_decode = decode($point_encode);
            if(!$point_decode && $point_decode != 0) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $user_id = $user->id;

                    $comment = Communication::find($comment_decode);
                    if(!$comment && $comment->user_id != $user_id) return response_error([],"参数有误，刷新一下试试");
                    $comment->decrement('favor_num');

                    $favors = Communication::where([
                        'type'=>5,'user_id'=>$user_id,
                        'line_id'=>$line_decode,'point_id'=>$point_decode,'reply_id'=>$comment_decode
                    ]);
                    $count = count($favors->get());
                    if($count)
                    {
                        $num = $favors->delete();
                        if($num != $count) throw new Exception("delete--commnucation--fail");
                    }

                    DB::commit();
                    return response_success();
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