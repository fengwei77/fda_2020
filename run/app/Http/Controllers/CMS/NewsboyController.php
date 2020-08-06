<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Mail\Newsletter_content;
use App\Mail\NewsletterDetail;
use App\Models\Newsletter;
use App\Models\Newsletter_tester;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Client;

class NewsboyController extends Controller
{
    //送報員
    public function index($id)
    {

        // send...
//        $artisan_run = Artisan::call('queue:listen');
        $mails = Subscriber::all()->toArray();
        $newsletter_detail = new NewsletterDetail();
        $query_result = Newsletter::where('id', $id)->first()->toArray();
        $newsletter_detail->subject = $query_result['subject'] ;
        $newsletter_detail->content = str_replace("/storage/", URL::to('/storage/') . '/', htmlspecialchars_decode($query_result['content']));
        $newsletter_detail->username = '';
        $newsletter_detail->email = '';
        $newsletter_detail->phone = '';
        $result = '';
        foreach ($mails as $item) {
            $result = Mail::to($item['email'])
                ->send(new Newsletter_content($newsletter_detail));
//              ->queue(new Newsletter_content($newsletter_detail));
        }

//        $client = new \GuzzleHttp\Client(['verify' => false]);
//        $client->request('GET', route('send.mail.listen'));

        return json_encode(['result' => '01', 'message' => $result]);
    }

    //
    public function test($id)
    {

        // send...
        $mails = Newsletter_tester::all()->toArray();

        $query_result = Newsletter::where('id', $id)->first()->toArray();
        $newsletter_detail = new NewsletterDetail();
        $newsletter_detail->subject = $query_result['subject'] .'測試信' ;

        $newsletter_detail->content = str_replace("/storage/", URL::to('/storage/') . '/', htmlspecialchars_decode($query_result['content']));
        $newsletter_detail->username = '';
        $newsletter_detail->email = '';
        $newsletter_detail->phone = '';
        $result = '';

        foreach ($mails as $item) {
            $result = Mail::to($item['email'])
                ->send(new Newsletter_content($newsletter_detail));
        }
//        $client = new \GuzzleHttp\Client(['verify' => false]);
//        $client->request('GET', route('send.mail.listen'));

        return json_encode(['result' => '01', 'message' => $result]);
    }
}
