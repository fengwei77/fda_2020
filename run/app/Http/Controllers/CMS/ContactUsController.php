<?php

namespace App\Http\Controllers\CMS;

use App\Mail\Newsletter_content;
use App\Mail\NewsletterDetail;
use App\Models\Contact_us;
use App\Models\Contact_us_replie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Auth;
use Session;
use Cache;

class ContactUsController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        $this->middleware(['auth']); // isAdmin 中介軟體讓具備指定許可權的用戶才能訪問該資源
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = '聯絡我們資料列表';
        $contactors = Cache::remember('cache_contact_us_queries', 60, function () {
            return Contact_us::paginate(50);
        });

        $reply_result = Contact_us_replie::get();
        return view('cms.contact_us.index', compact('page_title', 'contactors', 'reply_result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Contact_us::findOrFail($id);
        $result->delete();
        Cache::forget('cache_contact_us_queries');
        if ($result) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(FormBuilder $formBuilder, $id)
    {

        if (!isset($id)) {
            return Redirect::to('cms/dashboard')
                ->withErrors(['fail' => '無ID!']);
        }
        $page_title = '回覆內容';
        $form = $formBuilder->create('App\Forms\ReplyForm', [
            'method' => 'POST',
            'url' => route('cms.contact_us.store_reply')
        ]);
        $form->modify('cid', Field::HIDDEN, ['value' => $id]);
        $replies = '';
        //取提問者問題
        $question = Contact_us::where('id', '=', $id)->get();

        return view('cms.contact_us.reply', compact('page_title', 'replies', 'form', 'question'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_reply(Request $request)
    {
        $form = $this->form('App\Forms\ReplyForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $model = new Contact_us_replie();
        $model->fill([
            'cid' => $this->check_null($request->input('cid', ''), ''),
            'title' => $this->check_null($request->input('title', ''), ''),
            'subject' => $this->check_null($request->input('subject', ''), ''),
            'content' => htmlspecialchars($request->input('content', ''), ENT_QUOTES, "UTF-8"),
            'result' => $request->input('result', '0')
        ]);
        $model->save();


        $query_result = Contact_us::where('id', $request->input('cid', ''))->get()->toArray();
        $newsletter_detail = new NewsletterDetail();
        $newsletter_detail->subject = '問題回覆';
//        print_r($query_result);
        $newsletter_detail->content = str_replace("/storage/", URL::to('/storage/') . '/', $request->input('content', ''));
        $newsletter_detail->username = '';
        $newsletter_detail->email = '';
        $newsletter_detail->phone = '';
        $result = '';

        foreach ($query_result as $item) {
            $newsletter_detail->content = '詢問: <br>' . $item['content'] . '<br><br> 回覆內容: <br>' . $newsletter_detail->content;
            $result = Mail::to($item['email'])
                ->send(new Newsletter_content($newsletter_detail));
        }
        return redirect()->back()->with('success', '已發送回覆信件');

//        return redirect()->route('cms.contact_us.index');
    }

    function check_null($val = '', $def = '')
    {
        return is_null($val) ? $def : $val;
    }
}
