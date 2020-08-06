<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Auth;

class DashboardController extends Controller
{

    public function __construct() {
        $this->middleware(['auth']); // isAdmin 中介軟體讓具備指定許可權的用戶才能訪問該資源
    }

    public function index(){
        $page_title = '控制台';
        return view('cms.dashboard.index' , compact('page_title'));
    }
}
