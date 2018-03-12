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
    public function collect_course_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.collection.course')->with(['menu_collect_course'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->collect_course_get_list_datatable(request()->all());
    }
    // 【课程】收藏【删除】
    public function collect_course_deleteAction()
    {
        return $this->repo->collect_course_delete(request()->all());
    }
    // 【课程】收藏 列表
    public function collect_chapter_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.collection.chapter')->with(['menu_collect_chapter'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->collect_chapter_get_list_datatable(request()->all());
    }
    // 【章节】收藏【删除】
    public function collect_chapter_deleteAction()
    {
        return $this->repo->collect_chapter_delete(request()->all());
    }




    // 【课程】点赞 列表
    public function favor_course_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.favor.course')->with(['menu_favor_course'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->favor_course_get_list_datatable(request()->all());
    }
    // 【课程】点赞【删除】
    public function favor_course_deleteAction()
    {
        return $this->repo->favor_course_delete(request()->all());
    }


    // 【章节】点赞 列表
    public function favor_chapter_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.favor.chapter')->with(['menu_favor_chapter'=>'active']);
        else if(request()->isMethod('post')) return $this->repo->favor_chapter_get_list_datatable(request()->all());
    }
    // 【章节】点赞【删除】
    public function favor_chapter_deleteAction()
    {
        return $this->repo->favor_chapter_delete(request()->all());
    }





}
