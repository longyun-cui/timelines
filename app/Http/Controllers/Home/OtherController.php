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



    // 收藏列表
    public function collect_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.collect');
        else if(request()->isMethod('post')) return $this->repo->collect_get_list_datatable(request()->all());
    }
    // 收藏【删除】
    public function collect_deleteAction()
    {
        return $this->repo->collect_delete(request()->all());
    }



    // 收藏列表
    public function favor_viewList()
    {
        if(request()->isMethod('get')) return view('home.others.favor');
        else if(request()->isMethod('post')) return $this->repo->favor_get_list_datatable(request()->all());
    }
    // 收藏【删除】
    public function favor_deleteAction()
    {
        return $this->repo->favor_delete(request()->all());
    }





}
