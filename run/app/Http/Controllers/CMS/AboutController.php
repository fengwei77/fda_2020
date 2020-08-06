<?php

namespace App\Http\Controllers\cms;

use Carbon\Carbon;
use App\Models\About;
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


class AboutController extends BaseController
{
    use FormBuilderTrait;


    public function index(Request $request)
    {
        $page_title = '企業介紹';
//        2種方式都可以  speed目前還看不出來
        $result = About::paginate(15);
//        $result = Products::select(['*', DB::raw('IF(`position` <> 0, `position`, 9999999) `position`')])
//        ->orderBy('position', 'asc')->paginate(15);
        return view('cms.about.index', compact('page_title', 'result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $page_title = '編輯企業介紹';

        $form = $formBuilder->create('App\Forms\AboutForm', [
            'method' => 'POST',
            'url' => route('cms.about.store')
        ]);
        $content = '';
        $query_result = About::orderBy('created_at', 'desc')->first();
         if($query_result){
            $query_result = $query_result->toArray();
            $form->modify('content', Field::TEXTAREA, [
                'value' => htmlspecialchars_decode($query_result['content'])
            ]);
        }
        return view('cms.about.create', compact('page_title', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $new_uuid = Uuid::generate()->string;
        $form = $this->form('App\Forms\AboutForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $model = new About();
        $model->fill([
            'lang' => $this->check_null($request->input('lang', ''), ''),
            'uuid' => $new_uuid,
            'title' => '',
            'description' => '',
            'item_date' => $this->check_null($request->input('item_date', ''), now()),
            'content' => htmlspecialchars($request->input('content', ''), ENT_QUOTES, "UTF-8"),
            'images' => '',
            'files' => '',
            'hot' => 0,
            'view_count' => 0,
            'sequence_number' => 0,
            'verify' => $request->input('verify', '0'),
            'item_status' => $request->input('item_status', '0'),
            'result' => $request->input('result', '0')
        ]);
        $model->save();

        return redirect()->route('cms.about.create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
        if (!isset($id)) {
            return Redirect::to('cms/dashboard')
                ->withErrors(['fail' => '無ID!']);
        }
        $page_title = '編輯資料';

        $form = $formBuilder->create('App\Forms\AboutForm', [
            'method' => 'PUT',
            'url' => route('cms.about.update', $id)
        ]);

        $query_result = Advertisement::where('id', $id)->first()->toArray();

        $form
            ->add('id', Field::HIDDEN, ['value' => $query_result['id']])
            ->modify('start_date', Field::HIDDEN, ['value' => Carbon::createFromFormat('Y-m-d H:i:s', $query_result['start_date'])->format('Y-m-d')])
            ->modify('expire_date', Field::HIDDEN, ['value' => Carbon::createFromFormat('Y-m-d H:i:s', $query_result['expire_date'])->format('Y-m-d')])
            ->modify('item_status', Field::CHECKBOX, ['checked' => ($query_result['item_status'] == 1 ? true : false)]);
        return view('cms.about.edit', compact('page_title', 'form', 'query_result'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form = $this->form('App\Forms\AboutForm');

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $update_date = collect([
            'lang' => $this->check_null($request->input('lang', ''), ''),
            'layout_id' => $this->check_null($request->input('layout_id', ''), '0'),
            'title' => $this->check_null($request->input('title', ''), ''),
            'description' => $this->check_null($request->input('description', ''), ''),
            'item_date' => $this->check_null($request->input('item_date', ''), now()),
            'start_date' => $this->check_null($request->input('start_date', ''), ''),
            'expire_date' => $this->check_null($request->input('expire_date', ''), ''),
            'tags' => $this->check_null($request->input('tags', ''), ''),
            'files' => $this->check_null($request->input('files', ''), ''),
            'hot' => $this->check_null($request->input('hot', ''), '0'),
            'view_count' => 0,
            'sequence_number' => $this->check_null($request->input('sequence_number', '0'), '0'),
            'verify' => $request->input('verify', '0'),
            'item_status' => $request->input('item_status', '0'),
        ]);
        if ($request->input('images', '') != null) {
            $update_date->put('images', $request->input('images', ''));
        }
        Advertisement::where('id', $this->check_null($request->input('id', 'empty_id')))
            ->update($update_date->toArray());

        return redirect()->back()->with('success', '更新完成!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
        public function destroy($id)
    {
        //
        $item = Advertisement::findOrFail($id);
        $item->delete();
        if ($item) {
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
