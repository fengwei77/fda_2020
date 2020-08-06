<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShareController extends Controller
{

    public function __construct()
    {
    }

    public function index(Request $request)
    {

        $dt = $request->route('dt');      //结果为 1 ，获取的是第一个路由参数
        $file = $request->route('file');      //结果为 2 ，获取的是第二个路由参数

        $image_url = $dt . '/' . $file;
        return view('share/index' ,compact('image_url'));
    }

}
