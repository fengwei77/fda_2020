<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
//        $now = Carbon::now();
//        $open_date = Carbon::create(2020, 7, 11, 00, 00, 00);
//
//        if ($open_date->lt($now)) {
            return view('home.index');
//        } else {
//            return view('home.pre_open');
//        }
    }

    public function pre_open()
    {
        return view('home.pre_open');
    }
}
