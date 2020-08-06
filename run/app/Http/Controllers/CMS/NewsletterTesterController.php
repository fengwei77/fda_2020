<?php

namespace App\Http\Controllers\CMS;

use App\Models\Newsletter_tester;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class NewsletterTesterController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_title = '電子報測試人員信箱';

        $newsletter_testers = Newsletter_tester::orderBy('created_at', 'desc')->get()->toArray();

        return view('cms.newsletter_testers.index', compact('page_title', 'newsletter_testers'));
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email_arr = array_column(json_decode($request->input('emails', '')), 'value');
        $model = new Newsletter_tester();
        $model::truncate();
        $finalArray = [];
        foreach ($email_arr as $item) {
            array_push($finalArray, array(
                'newsletter_id' => 0,
                'name' => '',
                'email' => $item,
                'created_at' => now(),
                'updated_at' => now(),
                 )
            );
        }

        Newsletter_tester::insert($finalArray);

        return redirect()->route('cms.newsletter_testers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
