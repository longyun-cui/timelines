<?php

namespace App\Http\Controllers\Home;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Home\HomeRepository;


class HomeController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new AdminRepository;
    }


    public function index()
    {
        return view('home.index');
    }



}
