<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Cache;
use Session;


class SubscriberController extends Controller
{
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
        $page_title = '訂閱者';
//        $subscribers = Cache::remember('cache_subscribers_queries', 1, function () {
//            return Subscriber::where('email',Request('keyword'))->paginate(50);
//        });
//        $subscribers =  Subscriber::search(Request('keyword'))->paginate(50); //透過權重搜尋
        if ($request->has(['q'])) {
            $subscribers = Subscriber::where('email', 'LIKE', '%' . $request->input('q') . '%')->orderBy('created_at', 'desc')->paginate(5);
        }else{
            $subscribers = Subscriber::orderBy('created_at', 'desc')->paginate(5);
        }

        $row_number = ($subscribers->currentPage() - 1) * $subscribers->perPage() + 1 ;

        return view('cms.subscribers.index' , compact('page_title', 'row_number','subscribers'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
