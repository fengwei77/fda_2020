<?php

namespace App\Http\Controllers\CMS;

use App\Models\Invoice;
use App\Models\Player;
use App\Models\Prize;
use App\Models\Lucky_draw_logs;
use HttpException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use Debugbar;
use Webpatser\Uuid\Uuid;

//放在namespace下面。

Debugbar::info('this is a Info Message!');
Debugbar::error('this is an Error Message!');
Debugbar::warning('This is a Warning Message!');
Debugbar::addMessage('Another Message', 'mylable');

class LuckyDrawController extends BaseController
{
    use FormBuilderTrait;

    public function index()
    {
        //'lotte'
    }


    public function draw_test($uuid)
    {
        Debugbar::info('this is a Info Message!');
        Debugbar::error('this is an Error Message!');
        Debugbar::warning('This is a Warning Message!');
        Debugbar::addMessage('Another Message', 'mylable');

        set_time_limit(0);

        $prize_uuid = $uuid;
        $prize_name = '';
        $qty = 0;
        $pbb = 0;
        $day_limit = 0;
        $usage_sum = 0;
        $draw_count = 0;
        $start_date = '2000/01/01 00:00:00';
        $expire_date = '2000/01/01 00:00:00';
        $count_qualify_player_result = 0;
        $players_data_count = 0;
        //已抽獎數量
        $insert_batch_array = array();
        $update_batch_array = array();

        $prize_data = Prize::where('uuid', $uuid)->lockForUpdate()->first();
        //1 先檢查是否已經抽過獎
        if ($prize_data === null) {
            $result = [
                'code' => '020',
                'status' => 'failed',
                'message' => '獎項內容有誤!'
            ];
            return $result;
        }
        if ($prize_data['has_draw'] === 1) {
            $result = [
                'code' => '030',
                'status' => 'failed',
                'message' => '此獎項已被抽過了!'
            ];
            return $result;
        }
        //2 取獎項資料
        $prize_uuid = $prize_data['uuid'];
        $prize_id = $prize_data['id'];
        $prize_name = $prize_data['name'];
        $qty = $prize_data['qty'];
        $pbb = $prize_data['pbb'];
        $day_limit = $prize_data['day_limit'];
        $usage_sum = $prize_data['usage_sum'];
        $start_date = $prize_data['start_date'];
        $expire_date = $prize_data['expire_date'];
        //3 先判斷是否有母體可抽
        $players_data = Player::where('prize', '')
            ->where('item_status', '1')
            ->where('has_deleted', '0')
            ->whereBetween('created_at', [$start_date, $expire_date])
            ->lockForUpdate()->get()->toArray();
        $players_data_count = sizeof($players_data);
        //4 判斷剩下獎品數量和有資格會員人數皆都不可等於0
//            echo $players_data_count;
//            echo $qty - $usage_sum;
        if ($players_data_count == 0) {
            $result = [
                'code' => '040',
                'status' => 'failed',
                'message' => '目前沒有足夠符合資格的人數可以抽獎!'
            ];
            return $result;
        }
        if (($qty - $usage_sum) <= 0) {
            $result = [
                'code' => '050',
                'status' => 'failed',
                'message' => '目前沒有足夠的獎項數量可以抽獎!'
            ];
            return $result;

        }
        //包覆在transaction
        DB::beginTransaction();
        try {
            //5 開始抽獎動作
            while ($qty != 0) {
//                $players_data = Players::where('prize', '')->where('item_status', '1')->where('has_deleted', '0')->whereBetween('created_at', [$start_date, $expire_date])->lockForUpdate()->get();
//                $players_data_count = $players_data->count();
                if ($players_data_count <= 0) {
                    $players_data_count = -1;
                    break; //已無母體離開抽獎
                }
                //<editor-fold desc="得獎者資料">
                $player['uuid'] = '';
                $player['username'] = '';
                $player['phone'] = '';
                $player['email'] = '';
                $player['address'] = '';
                $player['prize_uuid'] = '';
                $player['prize'] = '0';
                $player['fb_id'] = 'facebook';
                //</editor-fold>

                //<editor-fold desc="取得得獎人資料">
                $rand_position = mt_rand(0, $players_data_count - 1);  //取得可以抽獎名單總數量,亂數位置
//                $query_qualify_player = Players::where('prize', '')
//                    ->where('item_status', '1')
//                    ->where('has_deleted', '0')
//                    ->whereBetween('created_at', [$start_date, $expire_date])
//                    ->offset($rand_position)
//                    ->limit(1)->lockForUpdate()
//                    ->first()->toArray();

                //方法三
                $query_qualify_player = $players_data[$rand_position];
                $players_data = $this->unsetForId($query_qualify_player['id'], $players_data);
//                array_splice($players_data,$rand_position,1);
//                $players_data_count = sizeof($players_data);
                //print_r($query_qualify_player);

                $player['uuid'] = $query_qualify_player['uuid'];
                $player['username'] = $query_qualify_player['username'];
                $player['phone'] = $query_qualify_player['phone'];
                $player['email'] = $query_qualify_player['email'];
                $player['address'] = $query_qualify_player['address'];
                $player['prize_uuid'] = $prize_uuid;
                $player['prize'] = $prize_name;
                $player['fb_id'] = $query_qualify_player['fb_id'];

//                                print_r($prize_data);
                echo '$players_data_count-' . sizeof($players_data);

//                exit();
                //</editor-fold>

                //6 刷新會員及新增得獎名單資料表
                if ($player['uuid'] != '') {
//                    $update_temp_array = array(
//                        'uuid' => $player['uuid'],
//                        'prize_uuid' => $player['prize_uuid'],
//                        'prize' => $player['prize']
//                    );
                    Players::where('uuid', $player['uuid'])
                        ->update([
                            'prize_uuid' => $player['prize_uuid'],
                            'prize' => $player['prize'],
                            'win_status' => 1
                        ]);
                    $players_data_count--;
//                    array_push($update_batch_array, $update_temp_array);
//                    unset($update_temp_array);

                    //新增紀錄
//                    $insert_temp_array = array(
//                        'player_uuid' => $player['uuid'],
//                        'fb_id' => $player['fb_id'],
//                        'fb_name' => '',
//                        'fb_email' => '',
//                        'username' => $player['username'],
//                        'phone' => $player['phone'],
//                        'email' => $player['email'],
//                        'gender' => '',
//                        'address' => $player['address'],
//                        'prize_uuid' => $player['prize_uuid'],
//                        'prize' => $player['prize'],
//                        'item_status' => '1',
//                        'has_deleted' => '0'
//                    );
                    $id = Lucky_draw_logs::create(['player_uuid' => $player['uuid'],
                        'player_uuid' => $player['uuid'],
                        'fb_id' => $player['fb_id'],
                        'fb_name' => '',
                        'fb_email' => '',
                        'username' => $player['username'],
                        'phone' => $player['phone'],
                        'email' => $player['email'],
                        'gender' => '',
                        'address' => $player['address'],
                        'prize_uuid' => $player['prize_uuid'],
                        'prize' => $player['prize'],
                        'item_status' => '1',
                        'has_deleted' => '0'
                    ])->id;
//
//                    array_push($insert_batch_array, $insert_temp_array);
//                    unset($insert_temp_array);
                    //7 同一個人也都標記 條件另外設定
                    $players_same_data = Player::where(function ($query) use ($player) {
                        $query->where('phone', $player['phone'])
                            ->orWhere('email', $player['email']);
                    })
                        ->Where('item_status', '1')
                        ->Where('has_deleted', '0')
                        ->where('prize', '')
                        ->whereBetween('created_at', [$start_date, $expire_date])->lockForUpdate()->get()->toArray();
                    foreach ($players_same_data as $item) {
//                        $update_temp_array = array(
//                            'prize_uuid' => $player['prize_uuid'],
//                            'prize' => '!mark_it!',
//                            'win_status' => 2
//                        );
                        Player::where('uuid', $item['uuid'])
                            ->update([
                                'prize_uuid' => $player['prize_uuid'],
                                'prize' => '!mark_it!',
                                'win_status' => 2
                            ]);
                        $players_data = $this->unsetForId($item['id'], $players_data);

                        $players_data_count--;
//                        array_push($update_batch_array, $update_temp_array);
//                        unset($update_temp_array);
                    }
                    //8 完成後獎項數量減一,一直到零
                    $qty--;
                    $draw_count++;
                    //更新會員狀態
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            $result = [
//                'code' => '060',
//                'status' => 'failed',
//                'message' => '資料處理中,請稍後重新執行!'
//            ];
//            return $result;

        }
        $result = [
            'code' => '000',
            'status' => 'success',
            'message' => '抽獎完成'
        ];
        print_r($result);

    }

    public function awards($uuid)
    {
        $page_title = '得獎名單';
        $prize_data = Prize::where('uuid', $uuid)->first();
        $result = Lucky_draw_logs::where('prize_uuid', $uuid)->where('has_deleted', 0)->paginate(200);
        return view('cms.prize.awards', compact('page_title', 'result', 'prize_data', 'uuid'));
    }

    public function export_awards($uuid)
    {
        $result = Lucky_draw_logs::select('username', 'phone', 'email', 'address', 'prize', 'created_at')->where('prize_uuid', $uuid)->where('has_deleted', 0)->get()->toArray();
        $writer = WriterEntityFactory::createXLSXWriter();
        $filePath = '';
        $ymd = date('Ymd');
        $folder = 'files/export/' . $ymd . '/';
        if (!Storage::disk('public')->has($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }
        $filename = time() . '.xlsx';
        $filePath = 'public/storage/' . $folder . $filename;
        $writer->openToFile($filePath);

        $multipleRows = [];
        $cells = [
            WriterEntityFactory::createCell(''),
            WriterEntityFactory::createCell('獎項'),
            WriterEntityFactory::createCell('姓名'),
            WriterEntityFactory::createCell('電話'),
            WriterEntityFactory::createCell('信箱'),
            WriterEntityFactory::createCell('地址'),
            WriterEntityFactory::createCell('日期'),
        ];
        $singleRow = WriterEntityFactory::createRow($cells);
        $writer->addRow($singleRow);
        $seq = 1;
        foreach ($result as $item) {
            $cells = [
                WriterEntityFactory::createCell($seq++),
                WriterEntityFactory::createCell($item['prize']),
                WriterEntityFactory::createCell($item['username']),
                WriterEntityFactory::createCell($item['phone']),
                WriterEntityFactory::createCell($item['email']),
                WriterEntityFactory::createCell($item['address']),
                WriterEntityFactory::createCell($item['created_at']),
            ];
            array_push($multipleRows, WriterEntityFactory::createRow($cells));
        }
        $writer->addRows($multipleRows);
        $writer->close();

        return $folder . $filename;
//        return Storage::disk('public')->download($folder . $filename);  // storage/app/public/downloads/test.txt
    }

    /**
     * 執行抽獎
     * @param Request $request
     * @param $uuid
     * @param $lottery_list
     * @return array
     */
    public function draw(Request $request, $uuid, $lottery_list)
    {

        set_time_limit(0);
        $tables = ['players', 'invoices'];
        $draw_source = [new Player, new Invoice];
        $prize_uuid = $this->check_null($request->input('uuid'), '');
        if (strlen($prize_uuid) < 36) {
            $result = [
                'code' => '010',
                'status' => 'failed',
                'message' => '獎項處理有誤,請重新操作!'
            ];
            return $result;
        }
        $prize_name = '';
        $qty = 0;
        $pbb = 0;
        $day_limit = 0;
        $usage_sum = 0;
        $draw_count = 0;
        $start_date = '2000/01/01 00:00:00';
        $expire_date = '2000/01/01 00:00:00';
        $count_qualify_player_result = 0;
        $players_data_count = 0;

        //1 先檢查是否已經抽過獎
        $prize_data = Prize::where('uuid', $prize_uuid)->lockForUpdate()->first();
        if ($prize_data === null) {
            $result = [
                'code' => '020',
                'status' => 'failed',
                'message' => '獎項內容有誤!'
            ];
            return $result;
        }
        if ($prize_data['has_draw'] === 1) {
            $result = [
                'code' => '030',
                'status' => 'failed',
                'message' => '此獎項已被抽過了!'
            ];
            return $result;
        }

        //2 取獎項資料
        $prize_uuid = $prize_data['uuid'];
        $prize_id = $prize_data['id'];
        $prize_name = $prize_data['name'];
        $qty = $prize_data['qty'];
        $pbb = $prize_data['pbb'];
        $day_limit = $prize_data['day_limit'];
        $usage_sum = $prize_data['usage_sum'];
        $start_date = $prize_data['start_date'];
        $expire_date = $prize_data['expire_date'];
        //3 先判斷是否有母體可抽
//        $players_data = Players::where('prize', '')
//            ->where('item_status', '1')
//            ->where('has_deleted', '0')
//            ->whereBetween('created_at', [$start_date, $expire_date])
//            ->lockForUpdate()->get()->toArray();

//        $players_data = DB::table('players')->select(DB::raw('id,uuid,fb_id,username,phone,email,address,prize_uuid,prize'))
//            ->whereRaw('item_status = ? AND has_deleted = ? ', [1, 0])
//            ->whereBetween('created_at', [$start_date, $expire_date])
//            ->lockForUpdate()
//            ->get()
//            ->groupBy('fb_id')
//            ->toArray();
        $select_filed = 'id,uuid,fb_id,username,phone,email,address,prize_uuid,prize';
        if ($lottery_list) {
            $select_filed = 'id,uuid,fb_id,username,phone,email,address,invoice,invoice_date,invoice_code,prize_uuid,prize';
        }
        $players_data = DB::table($tables[$lottery_list])->select(DB::raw($select_filed))
            ->Where(function ($query) {
                $query->whereRaw('item_status = ? AND has_deleted = ? ', [1, 0]);
            })
            ->whereBetween('created_at', [$start_date, $expire_date])
            ->lockForUpdate()
            ->get()
            ->toArray();


        $players_data_count = sizeof($players_data);
//        return $players_data_count;
        //4 判斷剩下獎品數量和有資格會員人數皆都不可等於0
        if ($players_data_count == 0) {
            $result = [
                'code' => '040',
                'status' => 'failed',
                'message' => '目前沒有足夠符合資格的人數可以抽獎!'
            ];
            return $result;
        }
        if (($qty - $usage_sum) <= 0) {
            $result = [
                'code' => '050',
                'status' => 'failed',
                'message' => '目前沒有足夠的獎項數量可以抽獎!'
            ];
            return $result;

        }
        //5 開始抽獎動作

        //包覆在transaction
        DB::beginTransaction();
        try {
            while ($qty != 0) {
                if ($players_data_count <= 0) {
                    $players_data_count = -1;
                    break; //已無母體離開抽獎
                }
                //<editor-fold desc="得獎者資料">
                $player['uuid'] = '';
                $player['username'] = '';
                $player['phone'] = '';
                $player['email'] = '';
                $player['address'] = '';
                $player['invoice'] = '';
                $player['invoice_date'] = date('Y/m/d');
                $player['invoice_code'] = '';
                $player['prize_uuid'] = '';
                $player['prize'] = '0';
                $player['fb_id'] = 'facebook';
                //</editor-fold>

                //<editor-fold desc="取得得獎人資料">
                $rand_position = mt_rand(0, $players_data_count - 1);  //取得可以抽獎名單總數量,亂數位置

                $query_qualify_player = (array)$players_data[$rand_position];
                unset($players_data[$rand_position]);
                $players_data = array_values($players_data);
                $player['uuid'] = $query_qualify_player['uuid'];
                $player['username'] = $query_qualify_player['username'];
                $player['phone'] = $query_qualify_player['phone'];
                $player['email'] = $query_qualify_player['email'];
                $player['address'] = $query_qualify_player['address'];
                if ($lottery_list) {
                    $player['invoice'] = $query_qualify_player['invoice'];
                    $player['invoice_date'] = $query_qualify_player['invoice_date'];
                    $player['invoice_code'] = $query_qualify_player['invoice_code'];
                }
                $player['prize_uuid'] = $prize_uuid;
                $player['prize'] = $prize_name;
                $player['fb_id'] = $query_qualify_player['fb_id'];
                //</editor-fold>

                //6 刷新會員及新增得獎名單資料表
                if ($player['uuid'] != '') {
                    $draw_source[$lottery_list]::where('uuid', $player['uuid'])
                        ->update([
                            'prize_uuid' => $player['prize_uuid'],
                            'prize' => $player['prize'],
                            'win_status' => 1
                        ]);
                    $players_data_count--;
                    //新增紀錄
                    $id = Lucky_draw_logs::create(['player_uuid' => $player['uuid'],
                        'player_uuid' => $player['uuid'],
                        'fb_id' => $player['fb_id'],
                        'fb_name' => '',
                        'fb_email' => '',
                        'username' => $player['username'],
                        'phone' => $player['phone'],
                        'email' => $player['email'],
                        'gender' => '',
                        'address' => $player['address'],
                        'invoice' => $player['invoice'],
                        'invoice_date' => $player['invoice_date'],
                        'invoice_code' => $player['invoice_code'],
                        'prize_uuid' => $player['prize_uuid'],
                        'prize' => $player['prize'],
                        'item_status' => '1',
                        'has_deleted' => '0'
                    ])->id;

                    //7 同一個人也都標記 條件另外設定
                    $players_same_data = $draw_source[$lottery_list]::where(function ($query) use ($player) {
                        $query->where('phone', $player['phone'])
                            ->orWhere('username', $player['username'])
//                            ->orWhere('fb_id', $player['fb_id'])
                            ->orWhere('email', $player['email']);
                    })
                        ->Where('item_status', '1')
                        ->Where('has_deleted', '0')
                        ->where('prize', '')
                        ->whereBetween('created_at', [$start_date, $expire_date])->lockForUpdate()->get()->toArray();
                    foreach ($players_same_data as $item) {
                        $draw_source[$lottery_list]::where('uuid', $item['uuid'])
                            ->update([
                                'prize_uuid' => $player['prize_uuid'],
                                'prize' => '!mark_it!',
                                'win_status' => 2
                            ]);
                        $players_data = $this->unsetForId($item['id'], $players_data);
                        $players_data_count--;
                    }
                    //8 完成後獎項數量減一,一直到零
                    $qty--;
                    $draw_count++;
                    //更新會員狀態
                }
            }

            //修改獎項狀態
            Prize::where('uuid', $prize_uuid)
                ->update([
                    'usage_sum' => $draw_count,
                    'has_draw' => 1
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
            $result = [
                'code' => '060',
                'status' => 'failed',
                'message' => '資料處理中,請稍後重新執行!'
            ];
            return $result;

        }
        $result = [
            'code' => '000',
            'status' => 'success',
            'message' => '抽獎完成'
        ];
        return $result;

    }

    /**
     * 清空得獎名單
     * @param Request $request
     * @param $uuid
     * @param $lottery_list
     * @return array
     */
    public function clear(Request $request, $uuid, $lottery_list)
    {
        set_time_limit(0);
        $draw_source = [new Player, new Invoice];
        $_uuid = $this->check_null($request->input('uuid'), '');

        if (strlen($uuid) < 36 && $uuid == $_uuid) {
            $result = [
                'code' => '010',
                'status' => 'failed',
                'message' => '獎項處理有誤,請重新操作!'
            ];
            return $result;
        }
        $prize_uuid = $uuid;
        //包覆在transaction
        try {
            DB::beginTransaction();
            //1 重置會員狀態
            $draw_source[$lottery_list]::where('prize_uuid', $prize_uuid)
                ->update([
                    'prize_uuid' => '',
                    'prize' => '',
                    'win_status' => 0
                ]);
            //2 刪除得獎紀錄
//            Lucky_draw_logs::where('prize_uuid', $prize_uuid)->forceDelete();
            Lucky_draw_logs::where('prize_uuid', $prize_uuid)
                ->update([
                    'has_deleted' => 1
                ]);
            Lucky_draw_logs::where('prize_uuid', $prize_uuid)->delete();

            //3 修改獎項狀態
            Prize::where('uuid', $prize_uuid)
                ->update([
                    'usage_sum' => 0,
                    'has_draw' => 0
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
            $result = [
                'code' => '010',
                'status' => 'failed',
                'message' => '資料處理中,請稍後重新執行!!'
            ];
            return $result;

        }
        $result = [
            'code' => '000',
            'status' => 'success',
            'message' => '清除完成'
        ];
        return $result;
    }

    function check_null($val = '')
    {
        return is_null($val) ? '' : $val;
    }

    function unsetForId($id, $array)
    {
        $found_key = array_search($id, array_column($array, 'id'));
        unset($array[$found_key]);
        $result_array = array_values($array);
        return $result_array;
    }


    /**
     * 執行抽獎
     * @param Request $request
     * @param $uuid
     * @param $lottery_list
     * @return array
     */
    public function opt_table(Request $request)
    {

        set_time_limit(0);

        $prize_name = '';
        $qty = 0;
        $pbb = 0;
        $day_limit = 0;
        $usage_sum = 0;
        $draw_count = 0;
        $start_date = '2020/03/23 00:00:00';
        $expire_date = '2020/05/01 00:00:00';
        $count_qualify_player_result = 0;
        $players_data_count = 0;


//        $players_data = DB::table('players')->select(DB::raw('id,uuid,fb_id,username,phone,email,address,prize_uuid,prize'))
//            ->Where(function ($query) {
//                $query->whereRaw('item_status = ? AND has_deleted = ? ', [1, 0]) ;
//            })
//            ->whereBetween('created_at', [$start_date, $expire_date])
//            ->lockForUpdate()
//            ->get()
//            ->toArray();

        $filter_group_date = DB::table('players')
            ->select('fb_id')
            ->groupBy('fb_id');

//        $players_data = DB::table('players')
//            ->joinSub($filter_group_date, 'filter_group_date', function ($join) {
//                $join->on('players.fb_id', '=', 'filter_group_date.fb_id');
//            })->get()
//            ->toArray();
//dd($players_data);
//        $players_data =  DB::table('players')->select(DB::raw('id,uuid,fb_id,fb_name,username,phone,email,address,prize_uuid,prize'))
//            ->groupBy(['id','uuid','fb_id','fb_name','username','email','address','prize_uuid','prize'])
//            ->orderBy('id')
//            ->get()
//            ->toArray();

//        return $players_data;

        $players_data = DB::table('players')->select(DB::raw('id,uuid,fb_id,fb_name,username,phone,email,address,prize_uuid,prize'))
            ->whereBetween('created_at', [$start_date, $expire_date])
            ->lockForUpdate()
            ->get()
            ->groupBy('fb_id')
            ->toArray();


        $players_data_count = sizeof($players_data);
//        return $players_data_count;
//        dd($players_data);
        foreach ($players_data as $index => $row) {
//            echo  sizeof($row);
//            echo '<br>';
//            if(sizeof($row) < 300) {
            $id = Temp_players::create([
                'uuid' => Uuid::generate()->string,
                'fb_id' => $row[0]->fb_id,
                'fb_name' => $row[0]->fb_name,
                'fb_email' => '',
                'username' => $row[0]->username,
                'phone' => $row[0]->phone,
                'email' => $row[0]->email,
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
//            }
        }

        $result = [
            'code' => '000',
            'status' => 'success',
            'message' => '完成'
        ];
        return $result;

    }
}
