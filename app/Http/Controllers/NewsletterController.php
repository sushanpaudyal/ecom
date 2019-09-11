<?php

namespace App\Http\Controllers;

use App\Newsletter;
use Illuminate\Http\Request;

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
}
