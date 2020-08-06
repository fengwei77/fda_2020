<?php

namespace App\Http\Controllers\CMS;

use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function index(){
        Auth::logout();
        $count = \DB::table('users')->count();
        if ($count == 0) {
            $user = User::create([
                'name' => 'administrator',
                'account' => 'administrator',
                'password' => '1111',
                'email' => '',
                'email_verified_at' => now(),
                'remember_token' => '',
                'item_status' => 1,
            ]);
            DB::insert('insert into model_has_roles (role_id, model_type,model_id) values (?, ?, ?)', [1, 'App\\Models\\User', 1]);
            DB::insert('insert into permissions (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [1, 'Administer roles & permissions', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into permissions (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [2, 'Create', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into permissions (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [3, 'Read', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into permissions (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [4, 'Delete', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into permissions (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [5, 'Edit', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );

            DB::insert('insert into roles (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [1, 'isAdmin', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into roles (id, name, guard_name, created_at, updated_at) values (?, ?, ?, ?, ?)',
                [2, 'general', 'web', date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]
            );
            DB::insert('insert into role_has_permissions (permission_id, role_id) values (?, ?)', [1, 1]);
            DB::insert('insert into role_has_permissions (permission_id, role_id) values (?, ?)', [2, 1]);
            DB::insert('insert into role_has_permissions (permission_id, role_id) values (?, ?)', [3, 1]);
            DB::insert('insert into role_has_permissions (permission_id, role_id) values (?, ?)', [4, 1]);
            DB::insert('insert into role_has_permissions (permission_id, role_id) values (?, ?)', [5, 1]);
        }
        return view('cms.login.index');
    }

    public function checker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account'=>'required|max:255',
            'password'=> 'required|unique:users|max:255',
        ]);

        if ($validator->passes()) {
            $attempt = Auth::attempt([
                'account' => $request->input('account'),
                'password' => $request->input('password'),
            ]);

            Log::channel('logging')->info('此用戶嘗試登入', ['user' => $request->input('account') ,'IP' => $request->getClientIp()]);

            if ($attempt) {
                return Redirect::intended('cms/dashboard');
            }
        }

        return Redirect::to('cms/login')
            ->withErrors(['fail'=>'帳號或密碼有誤!']);

    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->flush();
        return Redirect::to('cms/login');
    }
}
