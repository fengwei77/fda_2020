<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PersonController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        return view('invoice.person' );
    }

}
