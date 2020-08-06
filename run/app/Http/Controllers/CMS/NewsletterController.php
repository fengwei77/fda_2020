<?php

namespace App\Http\Controllers\CMS;

use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Auth;
use Session;

class NewsletterController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
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
        $page_title = '電子報列表';
        if ($request->has(['q'])) {
            $keyword = $request->input(['q']);
            $newsletters = Newsletter::where(function ($query) use ($keyword) {
                $query->where('subject', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('content', 'LIKE', '%' . $keyword . '%');
            })->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $newsletters = Newsletter::orderBy('created_at', 'desc')->paginate(5);
        }
        $row_number = ($newsletters->currentPage() - 1) * $newsletters->perPage() + 1 ;

        return view('cms.newsletters.index', compact('page_title', 'row_number', 'newsletters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $page_title = '新增電子報內容';
        $form = $formBuilder->create('App\Forms\NewsletterForm', [
            'method' => 'POST',
            'url' => route('cms.newsletters.store')
        ]);

        return view('cms.newsletters.create', compact('page_title', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $this->form('App\Forms\NewsletterForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $model = new Newsletter();
        $model->fill([
            'title' => $this->check_null($request->input('title', ''), ''),
            'subject' => $this->check_null($request->input('subject', ''), ''),
            'content' => htmlspecialchars($request->input('content', ''), ENT_QUOTES, "UTF-8"),
            'attachment' => $request->input('files', '') == null ? '' : $request->input('files', ''),
            'scheduled_time' => $request->input('scheduled_time', date("Y-m-d h:m:s")) == null ? date("Y-m-d h:m:s") :$request->input('scheduled_time', date("Y-m-d h:m:s")),
            'item_status' => $request->input('item_status', '0'),
            'result' => $request->input('result', '0')
        ]);
        $model->save();

        return redirect()->route('cms.newsletters.index');
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
        $page_title = '編輯產品資料';

        $form = $formBuilder->create('App\Forms\NewsletterForm', [
            'method' => 'PUT',
            'url' => route('cms.newsletters.update',$id)
        ]);


        $query_result = Newsletter::where('id', $id)->first()->toArray();

        $form
            ->modify('title', Field::TEXT, ['value' => $query_result['title']])
            ->modify('subject', Field::TEXT, ['value' => $query_result['subject']])
            ->modify('content', Field::TEXTAREA, [
                'value' => htmlspecialchars_decode($query_result['content'])
            ])
            ->modify('files', Field::HIDDEN, ['value' => $query_result['attachment']])
            ->modify('scheduled_time', Field::HIDDEN, ['value' => Carbon::createFromFormat('Y-m-d H:i:s', $query_result['scheduled_time'])->format('Y-m-d')])
            ->modify('item_status', Field::CHECKBOX, ['checked' => ($query_result['item_status'] == 1 ? true : false)]);

        return view('cms.newsletters.edit', compact('page_title', 'form', 'query_result'));
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
        $form = $this->form('App\Forms\NewsletterForm');

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        Newsletter::where('id', $id)
            ->update([
                'title' => $this->check_null($request->input('title', ''), ''),
                'subject' => $this->check_null($request->input('subject', ''), ''),
                'content' => htmlspecialchars($request->input('content', ''), ENT_QUOTES, "UTF-8"),
                'attachment' => $request->input('files', '') == null ? '' : $request->input('files', ''),
                'scheduled_time' => $request->input('scheduled_time', date("Y-m-d h:m:s")),
                'item_status' => $request->input('item_status', '0'),
                'result' => $request->input('result', '0')
            ]);

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
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();
        if ($newsletter) {
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
