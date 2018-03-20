<?php

namespace App\Http\Controllers\Home;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Services\Home\NotificationService;
use App\Repositories\Home\NotificationRepository;


class NotificationController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
//        $this->service = new NotificationService;
        $this->repo = new NotificationRepository;
    }


    public function index()
    {
        return $this->repo->index();
    }


    // 【评论】
    public function comment()
    {
        return $this->repo->comment(request()->all());
    }
    // 【点赞】
    public function favor()
    {
        return $this->repo->favor(request()->all());
    }





}
