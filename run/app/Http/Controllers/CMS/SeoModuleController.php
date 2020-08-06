<?php

namespace App\Http\Controllers\CMS;

use App\Forms\SeoModuleForm;
use App\Http\Controllers\Controller;
use App\Models\SeoModule;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Webpatser\Uuid\Uuid;
use Session;


class SeoModuleController extends Controller
{
    use FormBuilderTrait;

    public function __construct() {
        $this->middleware(['auth']); // isAdmin 中介軟體讓具備指定許可權的用戶才能訪問該資源
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_title = 'SEO管理';
        if ($request->has(['q'])) {
            $seo_modules = SeoModule::where('content', 'LIKE', '%' . $request->input('q') . '%')->orderBy('created_at', 'desc')->paginate(5);
        }else{
            $seo_modules = SeoModule::orderBy('created_at', 'desc')->paginate(5);
        }

        $row_number = ($seo_modules->currentPage() - 1) * $seo_modules->perPage() + 1 ;

        return view('cms.seo_module.index' , compact('page_title', 'row_number','seo_modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $form = $this->form('App\Forms\SeoModuleForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $model = new SeoModule();
        $model->fill([
            'lang' => $this->check_null($request->input('lang', ''), ''),
            'property_name' => $this->check_null($request->input('property_name', ''), ''),
            'property_value' => $this->check_null($request->input('property_value', ''), ''),
            'content' => $this->check_null($request->input('content', ''), ''),
            'sequence_number' =>  $this->check_null($request->input('sequence_number', '0'), '0'),
            'verify' => $request->input('verify', '0'),
            'item_status' => $request->input('item_status', '0'),
            'result' => $request->input('result', '0')
        ]);
        $model->save();

        return redirect()->route('cms.seo_module.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Subscriber::findOrFail($id);
        $result->delete();
        Cache::forget('cache_subscribers_queries');
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
