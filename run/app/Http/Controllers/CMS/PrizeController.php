<?php

namespace App\Http\Controllers\CMS;

use App\Forms\PrizeForm;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Webpatser\Uuid\Uuid;
use Debugbar; //放在namespace下面。

Debugbar::info('this is a Info Message!');
Debugbar::error('this is an Error Message!');
Debugbar::warning('This is a Warning Message!');
Debugbar::addMessage('Another Message', 'mylable');


class PrizeController extends BaseController
{
    use FormBuilderTrait;

    public function index()
    {
        //'獎項'
        $page_title = '獎項列表';
//       $result = DB::table('construction_companies')->paginate(2);
        $result = Prize::orderBy('id', 'ASC')->paginate(15);
//        $result = Prizes::where('item_status',1)->where('has_deleted', 0)->where('has_draw', 0)->get();
////        print_r($result);
//        $result_object = $result->filter(function($value, $key) {
//            return $value->uuid == 'bbd72440-f0bb-11e9-a2ea-610e57764d72';
//        })->first();
////        print_r($result_object);
//        $filtered = $result->reject(function ($value, $key) {
//            return  $value->uuid == 'bbd72440-f0bb-11e9-a2ea-610e57764d72';
//        });
//        print_r($filtered);
//        exit();
//        Cache::forget('Prize_list');
//        if (Cache::has('Prize_list')) {
//            $result = Cache::get('Prize_list');
//        } else {
//            $result = Cache::remember('Prize_list', 10, function () {
//                return Prizes::orderBy('id', 'desc')->paginate(2);
//            });
//        }
        return view('cms.prize.index', compact('page_title', 'result'));
    }

    public function list(Request $request)
    {
        $page_title = '獎項列表';
//       $result = DB::table('construction_companies')->paginate(2);
        $result = Prize::orderBy('id', 'desc')->paginate(15);
//        $result = Prizes::where('item_status',1)->where('has_deleted', 0)->where('has_draw', 0)->get();
////        print_r($result);
//        $result_object = $result->filter(function($value, $key) {
//            return $value->uuid == 'bbd72440-f0bb-11e9-a2ea-610e57764d72';
//        })->first();
////        print_r($result_object);
//        $filtered = $result->reject(function ($value, $key) {
//            return  $value->uuid == 'bbd72440-f0bb-11e9-a2ea-610e57764d72';
//        });
//        print_r($filtered);
//        exit();
//        Cache::forget('Prize_list');
//        if (Cache::has('Prize_list')) {
//            $result = Cache::get('Prize_list');
//        } else {
//            $result = Cache::remember('Prize_list', 10, function () {
//                return Prizes::orderBy('id', 'desc')->paginate(2);
//            });
//        }
        return view('cms.prize.index', compact('page_title', 'result'));
    }

    public function listContent($id, Request $request)
    {

        $items = Prize::where('id', '=', $id)->order_by('created_at', 'desc')->paginate(5);
        $item_count = Prize::where('id', '=', $id)->count();

        if ($request->ajax()) {
            return json_encode(['items' => $items, 'item_count' => $item_count]);
        }
    }

    //新增獎項
    public function create(FormBuilder $formBuilder)
    {
        $page_title = '新增獎項資料';

        $form = $formBuilder->create('App\Forms\PrizeForm', [
            'method' => 'POST',
            'url' => route('cms.prize.store')
        ]);

        return view('cms.prize.create', compact('page_title', 'form'));
    }


    //編輯獎項
    public function edit(FormBuilder $formBuilder, $uuid)
    {
        if (!isset($uuid)) {
            return Redirect::to('cms/dashboard')
                ->withErrors(['fail' => '無UUID!']);
        }
        $page_title = '編輯獎項資料';

        $form = $formBuilder->create('App\Forms\PrizeForm', [
            'method' => 'PUT',
            'url' => route('cms.prize.update')
        ]);


        $query_result = Prize::where('uuid', $uuid)->first()->toArray();

        $form
            ->modify('uuid', Field::HIDDEN, ['value' => $uuid])
            ->modify('lottery_list', Field::HIDDEN, ['value' => $query_result['lottery_list']])
            ->modify('prize_type', Field::CHECKBOX, ['checked' => ($query_result['prize_type'] == 1 ? true : false)])
            ->modify('images', Field::HIDDEN, ['value' => $query_result['files']])
            ->modify('name', Field::TEXT, ['value' => $query_result['name']])
            ->modify('desc', Field::TEXT, ['value' => $query_result['desc']])
            ->modify('qty', Field::NUMBER, ['value' => $query_result['qty']])
            ->modify('usage_sum', Field::NUMBER, ['value' => $query_result['usage_sum']])
            ->modify('day_limit', Field::HIDDEN, ['value' => $query_result['day_limit']])
            ->modify('pbb', Field::HIDDEN, ['value' => $query_result['pbb']])
            ->modify('start_date', Field::TEXT, ['value' => $query_result['start_date']])
            ->modify('expire_date', Field::TEXT, ['value' => $query_result['expire_date']])
            ->modify('item_status', Field::CHECKBOX, ['checked' => ($query_result['item_status'] == 1 ? true : false)]);

        return view('cms.prize.edit', compact('page_title', 'form', 'query_result'));
    }

    //新增至資料庫

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $form = $this->form('App\Forms\PrizeForm');

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $new_uuid = Uuid::generate()->string;
        $id = Prize::create([
            'uuid' => $new_uuid,
            'event_id' => '001',
            'lottery_list' => $this->check_null($request->input('lottery_list'), '0'),
            'prize_type' => $this->check_null($request->input('prize_type'), '0'),
            'name' => $this->check_null($request->input('name'), ''),
            'desc' => $this->check_null($request->input('desc'), ''),
            'files' => $this->check_null($request->input('images'), ''),
            'qty' => intval($this->check_null($request->input('qty'), '0')),
            'usage_sum' => intval($this->check_null($request->input('usage_sum'), '0')),
            'day_limit' => intval($this->check_null($request->input('day_limit'), '0')),
            'pbb' => intval($this->check_null($request->input('pbb'), '0')),
            'start_date' => $this->check_null($request->input('start_date'), date("Y-m-d")) . ' 00:00:00',
            'expire_date' => $this->check_null($request->input('expire_date'), date_create('+1 months')->format('Y-m-d')) . ' 23:59:59',
            'has_draw' => 0,
            'item_status' => $this->check_null($request->input('item_status'), '0'),
            'has_deleted' => $this->check_null($request->input('has_deleted'), '0')
        ])->id;
        return redirect()->route('cms.prize.index');
    }


    public function update(Request $request)
    {
        $form = $this->form('App\Forms\PrizeForm');

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        Prize::where('uuid', $this->check_null($request->input('uuid', 'empty_uuid')))
            ->update([
                'event_id' => '001',
//                'lottery_list' => $this->check_null($request->input('lottery_list'), '0'),
                'prize_type' => $this->check_null($request->input('prize_type'), '0'),
                'name' => $this->check_null($request->input('name'), ''),
                'desc' => $this->check_null($request->input('desc'), ''),
                'files' => $this->check_null($request->input('images'), ''),
                'qty' => intval($this->check_null($request->input('qty'), '0')),
                'usage_sum' => intval($this->check_null($request->input('usage_sum'), '0')),
                'day_limit' => intval($this->check_null($request->input('day_limit'), '0')),
                'pbb' => intval($this->check_null($request->input('pbb'), '0')),
                'start_date' => $this->check_null($request->input('start_date'), date("Y-m-d")) . ' 00:00:00',
                'expire_date' => $this->check_null($request->input('expire_date'), date_create('+1 months')->format('Y-m-d')) . ' 23:59:59',
                'item_status' => $this->check_null($request->input('item_status'), '0'),
                'has_deleted' => $this->check_null($request->input('has_deleted'), '0')
            ]);

        return redirect()->back()->with('success', '更新完成!');

    }


    public function delete($uuid)
    {
        $result = Prize::where('uuid', $uuid)->forceDelete();

        if ($result) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
    }

    function check_null($val = '', $def = '')
    {
        return is_null($val) ? $def : $val;
    }
}
