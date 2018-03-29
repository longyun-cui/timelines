<?php
namespace App\Repositories\Home;

use App\Models\Line;
use App\Models\Point;
use App\Models\Communication;
use App\Models\Pivot_User_Item;
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



    // 返回【收藏】【线】列表数据
    public function collect_line_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Collection::with([
                'line'=>function($query) { $query->with(['user']); }
            ])->where(['type'=>1,'user_id'=>$user->id,'point_id'=>0]);
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
            if($list[$k]->line)
            {
                $list[$k]->line->encode_id = encode($v->line->id);
                $list[$k]->line->user->encode_id = encode($v->line->user->id);
            }
        }
        return datatable_response($list, $draw, $total);
    }
    // 删除【收藏】【线】
    public function collect_line_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $collection = Pivot_User_Collection::find($id);
        if($collection->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $line_id = $collection->line_id;
            $line = Line::find($line_id);
            if($line)
            {
                $line->decrement('collect_num');
            }

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



    // 返回【收藏】【点】列表数据
    public function collect_point_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Collection::with([
            'point'=>function($query) { $query->with(['user','line']); }
        ])->where(['type'=>1,'user_id'=>$user->id])->where('point_id','<>',0);
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
            if($list[$k]->point)
            {
                $list[$k]->point->encode_id = encode($v->point->id);
                $list[$k]->point->line->encode_id = encode($v->point->line->id);
                $list[$k]->point->user->encode_id = encode($v->point->user->id);
            }
        }
        return datatable_response($list, $draw, $total);
    }
    // 删除【收藏】【点】
    public function collect_point_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $collection = Pivot_User_Collection::find($id);
        if($collection->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $point_id = $collection->point_id;
            $point = Point::find($point_id);
            if($point)
            {
                $point->decrement('collect_num');
            }

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




    // 返回【点赞】【线】列表数据
    public function favor_line_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Item::with([
            'line'=>function($query) { $query->with(['user']); }
        ])->where(['type'=>1,'user_id'=>$user->id,'point_id'=>0]);
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
            if($list[$k]->line)
            {
                $list[$k]->line->encode_id = encode($v->line->id);
                $list[$k]->line->user->encode_id = encode($v->line->user->id);
            }
        }
        return datatable_response($list, $draw, $total);
    }
    // 删除【点赞】【线】
    public function favor_line_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $other = Pivot_User_Item::find($id);
        if($other->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $line_id = $other->line_id;
            $line = Line::find($line_id);
            if($line)
            {
                $line->decrement('favor_num');
            }

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


    // 返回【点赞】【点】列表数据
    public function favor_point_get_list_datatable($post_data)
    {
        $user = Auth::user();
        $query = Pivot_User_Item::with([
            'point'=>function($query) { $query->with(['user','line']); }
        ])->where(['type'=>1,'user_id'=>$user->id])->where('point_id','<>',0);
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
            if($list[$k]->point)
            {
                $list[$k]->point->encode_id = encode($v->point->id);
                $list[$k]->point->line->encode_id = encode($v->point->line->id);
                $list[$k]->point->user->encode_id = encode($v->point->user->id);
            }
        }
        return datatable_response($list, $draw, $total);
    }
    // 删除【点赞】【点】
    public function favor_point_delete($post_data)
    {
        $user = Auth::user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该课程不存在，刷新页面试试");

        $other = Pivot_User_Item::find($id);
        if($other->user_id != $user->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $point_id = $other->point_id;
            $point = Point::find($point_id);
            if($point)
            {
                $point->decrement('favor_num');
            }

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