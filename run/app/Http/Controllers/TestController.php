<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestController extends Controller
{
    public function test()
    {
        //添加角色
        Role::create(['name' => 'writer']);
    }
}
