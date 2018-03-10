<?php
namespace App\Repositories\Home;

use App\Models\Course;
use App\Models\Content;
use App\Models\Communication;
use App\Models\Pivot_User_Course;
use App\Models\Pivot_User_Collection;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;
use Symfony\Component\Console\Helper\Table;

class OtherRepository {

    private $model;
    public function __construct()
    {
    }

    public function index()
    {
        return view('home.index');
    }



    // 返回【收藏】列表数据
    public function collect_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Collection::with([
                'course'=>function($query) { $query->with(['user']); }
            ])->where(['type'=>1,'user_id'=>$user->id]);
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
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->course->encode_id = encode($v->course->id);
            $list[$k]->course->user->encode_id = encode($v->course->user->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 删除【收藏】
    public function collect_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $collection = Pivot_User_Collection::find($id);
        if($collection->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $collection->delete();
            if(!$bool) throw new Exception("delete--collection--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }




    // 返回列表数据
    public function favor_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Course::with([
                'course'=>function($query) { $query->with(['user']); }
            ])->where(['type'=>1,'user_id'=>$user->id]);
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
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->course->encode_id = encode($v->course->id);
            $list[$k]->course->user->encode_id = encode($v->course->user->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 删除【收藏】
    public function favor_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $other = Pivot_User_Course::find($id);
        if($other->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $other->delete();
            if(!$bool) throw new Exception("delete--other--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }




}