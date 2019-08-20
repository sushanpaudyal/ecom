<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function addCurrency(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $currency = new Currency;
            $currency->currency_code = $data['currency_code'];
            $currency->exchange_rate = $data['exchange_rate'];

            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }

            $currency->status = $status;
            $currency->save();
            return redirect()->back()->with('flash_message_success', 'Currency Added Successfully');

        }
        return view ('admin.currencies.add_currency');
    }

    public function viewCurrency(){
        $currencies = Currency::all();
        return view ('admin.currencies.view_currency', compact('currencies'));
    }

    public function editCurrency(Request $request, $id){
        $currency = Currency::findOrFail($id);
        $data = $request->all();
        if($request->isMethod('post')){
            $currency->currency_code = $data['currency_code'];
            $currency->exchange_rate = $data['exchange_rate'];

            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }

            $currency->status = $status;
            $currency->save();
            return redirect()->route('viewCurrency')->with('flash_message_success', 'Currency Updated Successfully');
        }
        return view ('admin.currencies.edit_currency', compact('currency'));
    }
}
