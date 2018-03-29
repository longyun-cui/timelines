<?php
namespace App\Repositories\Home;

use App\Http\Middleware\LoginMiddleware;
use App\Models\Line;
use App\Models\Point;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class PointRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new Point;
    }

    public function index()
    {
        return view('home.index');
    }

    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $line_encode = request("line_id",0);
        $line_decode = decode($line_encode);
        if(!$line_decode) return view('home.404');

        $user = Auth::user();
        $line = Line::find($line_decode);
        if(!$line || $line->user_id != $user->id) return view('home.404');

        $query = Point::select("*")->with(['user'])->where('line_id', $line_decode);
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 返回添加视图
    public function view_create()
    {
        $line_encode = request("line_id",0);
        $line_decode = decode($line_encode);
        if(!$line_decode && intval($line_decode) !== 0) return view('home.404');
        $user = Auth::user();
        $line = Line::find($line_decode);
        if(!$line || $line->user_id != $user->id) return view('home.404');
        $line->encode = encode($line->id);
        return view('home.point.edit')->with(['line'=>$line]);
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id && intval($id) !== 0) return view('home.404');

        if($decode_id == 0)
        {
            return view('home.point.edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = Point::with('line')->find($decode_id);
            if($data)
            {
                unset($data->id);
                $data->line->encode = encode($data->line->id);
                unset($data->line->id);
                return view('home.point.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data, 'line'=>$data->line]);
            }
            else return response("该课程不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'line_id.required' => '参数有误',
            'title.required' => '请输入课程标题',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'line_id' => 'required',
            'title' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $user = Auth::user();

        $operate = $post_data["operate"];
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error();


        DB::beginTransaction();
        try
        {
            if($operate == 'create') // $id==0，添加一个新的课程
            {
                $line_decode = decode($post_data["line_id"]);
                if(!$line_decode && intval($line_decode) !== 0) return response_error();

                $line = Line::find($line_decode);
                if(!$line || $line->user_id != $user->id) return response_error();
                $post_data["line_id"] = $line_decode;

                $point = new Point;
                $post_data["user_id"] = $user->id;
            }
            elseif('edit') // 编辑
            {
                $point = Point::find($id);
                if(!$point) return response_error([],"该课程不存在，刷新页面重试");
                if($point->user_id != $user->id) return response_error([],"你没有操作权限");
                unset($post_data["line_id"]);
            }
            else throw new Exception("operate--error");

            $bool = $point->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($point->id);

                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'unique-cover-points' , 'cover_point_'.$encode_id);
                    if($result["status"])
                    {
                        $point->cover_pic = $result["data"];
                        $point->save();
                    }
                    else throw new Exception("upload--cover--fail");
                }
            }
            else throw new Exception("insert--people--fail");


            DB::commit();
            return response_success(['id'=>$encode_id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
//            exit($e->getMessage());
//            $msg = $e->getMessage();
            $msg = '操作失败，请重试！';
            return response_fail([], $msg);
        }
    }

    // 删除
    public function delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $point = Point::find($id);
        if($point->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $point->delete();
            if(!$bool) throw new Exception("delete--point--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }

    // 启用
    public function enable($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该作者不存在，刷新页面试试");

        $point = Point::find($id);
        if($point->user_id != $user->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $point->fill($update)->save();
            if(!$bool) throw new Exception("update--point--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }

    // 禁用
    public function disable($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $point = Point::find($id);
        if($point->user_id != $user->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $point->fill($update)->save();
            if(!$bool) throw new Exception("update--point--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }


}