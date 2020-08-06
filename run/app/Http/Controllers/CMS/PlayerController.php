<?php

namespace App\Http\Controllers\CMS;


use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Webpatser\Uuid\Uuid;


class PlayerController extends BaseController
{
    use FormBuilderTrait;

    public function index(){
        //'user'
        $page_title = '玩家列表';
//       $result = DB::table('construction_companies')->paginate(2);
        $result = Player::orderBy('id', 'desc')->remember(10)->paginate(50);

        return view('cms.player.index' , compact('page_title','result'));
    }

    public function list(){
        $page_title = '玩家列表';
//       $result = DB::table('construction_companies')->paginate(2);
        $result = Player::remember(10)->paginate(50);

        return view('cms.player.index' , compact('page_title','result'));
    }

    public function listContent($id, Request $request)
    {

        $items = Player::where('id', '=', $id)->orderBy('created_at', 'desc')->paginate(5);
        $item_count = Player::where('id', '=', $id)->count();

        if ($request->ajax()) {
            return  json_encode(['items' => $items, 'item_count' => $item_count]);
        }
    }


    public function delete($uuid)
    {
        $result = Player::where('uuid', $uuid)->forceDelete();

        if ($result) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
    }

    function check_null($val = ''){
        return is_null($val) ? '' : $val  ;
    }
}
