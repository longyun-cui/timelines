<?php

namespace App\Http\Controllers\Home;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Services\Home\OtherService;
use App\Repositories\Home\OtherRepository;


class OtherController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
//        $this->service = new OtherService;
        $this->repo = new OtherRepository;
    }


    public function index()
    {
        return $this->repo->index();
    }



    // 【课程】收藏 列表
    public function collect_line_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.collection.line')->with(['menu_collect_line'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->collect_line_get_list_datatable(request()->all());
    }
    // 【课程】收藏【删除】
    public function collect_line_deleteAction()
    {
        return $this->repo->collect_line_delete(request()->all());
    }
    // 【课程】收藏 列表
    public function collect_point_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.collection.point')->with(['menu_collect_point'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->collect_point_get_list_datatable(request()->all());
    }
    // 【章节】收藏【删除】
    public function collect_point_deleteAction()
    {
        return $this->repo->collect_point_delete(request()->all());
    }




    // 【课程】点赞 列表
    public function favor_line_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.favor.line')->with(['menu_favor_line'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->favor_line_get_list_datatable(request()->all());
    }
    // 【课程】点赞【删除】
    public function favor_line_deleteAction()
    {
        return $this->repo->favor_line_delete(request()->all());
    }


    // 【章节】点赞 列表
    public function favor_point_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.favor.point')->with(['menu_favor_point'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->favor_point_get_list_datatable(request()->all());
    }
    // 【章节】点赞【删除】
    public function favor_point_deleteAction()
    {
        return $this->repo->favor_point_delete(request()->all());
    }





}
