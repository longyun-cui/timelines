<?php

namespace App\Http\Controllers\Home;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Line;

//use App\Services\Home\PointService;
use App\Repositories\Home\PointRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;


class PointController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
//        $this->service = new PointService;
        $this->repo = new PointRepository;
    }


    public function index()
    {
        return $this->repo->index();
    }

    // 列表
    public function viewList()
    {
        $line_encode = request("line_id",0);
        $line_decode = decode($line_encode);
        if(!$line_decode && intval($line_decode) !== 0) return view('home.404');

        $user = Auth::user();
        $line = Line::find($line_decode);
        if(!$line || $line->user_id != $user->id) return view('home.404');
        $line->encode = encode($line->id);

        if(request()->isMethod('get')) return view('home.point.list')->with(['line'=>$line]);
        else if(request()->isMethod('post')) return $this->repo->get_list_datatable(request()->all());
    }

    // 创建
    public function createAction()
    {
        return $this->repo->view_create();
    }

    // 编辑
    public function editAction()
    {
        if(request()->isMethod('get')) return $this->repo->view_edit();
        else if (request()->isMethod('post')) return $this->repo->save(request()->all());
    }

    // 【删除】
    public function deleteAction()
    {
        return $this->repo->delete(request()->all());
    }

    // 【分享】
    public function enableAction()
    {
        return $this->repo->enable(request()->all());
    }

    // 【取消分享】
    public function disableAction()
    {
        return $this->repo->disable(request()->all());
    }






}
