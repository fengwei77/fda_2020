<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Webpatser\Uuid\Uuid;

class RegisterController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {

        return view('player_data.register' );
    }


    public function save(Request $request)
    {

        $fail_invoice = [];
        $parameters = request()->except(['recaptcha']);
        $rules = [
            'username' => 'required|max:255',
            'birthday' => 'required|max:255',
            'mobile' => 'email|max:255',
            'phone' => 'required|max:255',
            'email' => 'email|max:255',
            'gender' => 'required|max:255',
            'country' => 'required|max:255',
            'district' => 'required|max:255',
            'address' => 'required|max:255',
        ];
        $messages = [
            'username.required' => '請輸入正確姓名',
            'phone.required' => '請輸入正確電話',
            'email.email' => '信箱格式有錯誤',
        ];

        $validator = Validator::make($parameters, $rules, $messages);

        $result = [
            'id' => 0,
            'code' => 0,
            'message' => '完成登錄',
        ];
        $id = Player::create([
            'uuid' => Uuid::generate()->string,
            'fb_id' => $request->input('fb_id'),
            'fb_name' => $request->input('fb_name'),
            'fb_email' => '',
            'username' => $request->input('username'),
            'birthday' => $request->input('birthday'),
            'phone' => $request->input('phone'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'country' => $request->input('country'),
            'district' => $request->input('district'),
            'address' => $request->input('address'),
            'score' => 0,
            'timer' => 0,
            'prize_uuid' => '',
            'prize' => '',
            'win_status' => '0',
            'item_status' => '1',
            'has_deleted' => '0'
        ])->id;
        $result = [
            'id' => $id,
            'code' => 0,
            'message' => '完成登錄'
        ];
        usleep(1000000);

        return response()->json($result);

    }
}
