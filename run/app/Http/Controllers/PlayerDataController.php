<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;


class PlayerDataController extends Controller
{
    //
    public function index(Request $request)
    {
    }


    public function save(Request $request)
    {

        $fail_invoice = [];
        $parameters = request()->except(['recaptcha', 'invoice']);
        $rules = [
            'username' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'email|max:255',
//            'address' => 'required|max:255',
//            'invoice' => 'regex:/^[a-zA-Z]{2}[-]?[0-9]{8}$/|in:' . $request->input('invoice')
        ];
        $messages = [
            'username.required' => '請輸入正確姓名',
            'phone.required' => '請輸入正確電話',
            'email.email' => '信箱格式有錯誤',
//            'address.required' => '請輸入正確地址',
//            'invoice.regex' => '請輸入正確發票',
        ];

        $validator = Validator::make($parameters, $rules, $messages);

        $result = [
            'id' => 0,
            'code' => 0,
            'message' => '完成登錄',
            'invoice' => ''
        ];
            $id = Player::create([
                'uuid' => Uuid::generate()->string,
                'fb_id' => $request->input('fb_id'),
                'fb_name' => $request->input('fb_name'),
                'fb_email' => '',
                'username' => $request->input('username'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'gender' => '',
                'country' => '',
                'district' => '',
                'address' => '',
                'invoice' => '',
                'invoice_date' => now(),
                'invoice_code' => '0000',
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
                'message' => '完成登錄',
                'invoice' => ''
            ];
        usleep(2000000);

        return response()->json($result);

    }


}
