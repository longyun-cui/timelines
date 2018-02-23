<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Services\Home\CourseService;
//use App\Repositories\Home\CourseRepository;


class CommonController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
//        $this->service = new CourseService;
//        $this->repo = new CourseRepository;
    }


    public function index()
    {
        return view('home.index');
    }

    public function change_captcha()
    {
        return response_success(['src'=>captcha_src()],'');
//        return response_success(['img'=>captcha_img()],'');
    }

}
