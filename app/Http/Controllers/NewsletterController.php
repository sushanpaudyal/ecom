<?php

namespace App\Http\Controllers;

use App\Newsletter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    public function checkSubscriber(Request $request){
        if($request->ajax()){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;
            $subscriberCount = Newsletter::where('email', $data['subscriber_email'])->count();
            if($subscriberCount > 0){
                echo "exists";
            }
        }
    }


    public function addSubscriber(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $subscriberCount = Newsletter::where('email', $data['subscriber_email'])->count();
            if($subscriberCount > 0){
                echo "exists";
            } else {
                // Add Newsletter email in newsletter subscriber table
                $newsletter = new Newsletter;
                $newsletter->email = $data['subscriber_email'];
                $newsletter->status = 1;
                $newsletter->save();
                echo "saved";
            }
        }
    }



    public function viewNewsletterSubscribers(){
        $newsletterss = Newsletter::get();
        return view ('admin.newsletters.view_newsletters', compact('newsletterss'));
    }

    public function updateNewsletterStatus($id, $status){
        Newsletter::where('id', $id)->update(['status'=> $status]);
        return redirect()->back()->with('flash_message_success', 'Newsletter Status has been Updated');
    }

    public function deleteNewsletter($id){
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();
        return redirect()->back()->with('flash_message_success', 'Newsletter has been Deleted');

    }

    public function exportNewsletterEmails(){
        $subsciberData = Newsletter::select('id','email','created_at')->where('status', 1)->orderBy('id', 'DESC')->get();
        $subsciberData = json_decode(json_encode($subsciberData), true);
//        echo "<pre>"; print_r($subsciberData); die;
        return Excel::create('subscribers'.rand(), function ($excel) use ($subsciberData){
            $excel->sheet('mySheet', function($sheet) use ($subsciberData){
                $sheet->fromArray($subsciberData);
            });
        })->download('xlsx');
    }
}
