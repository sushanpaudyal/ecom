<?php

namespace App\Http\Controllers;

use App\ShippingCharges;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function viewShipping(){
        $shipping_charges = ShippingCharges::get();
        return view ('admin.shipping.view_shipping', compact('shipping_charges'));
    }

    public function editShipping(Request $request, $id){
        $shipping = ShippingCharges::findOrFail($id);
        if($request->isMethod('post')){
            $data = $request->all();
            $shipping->country = $data['country'];
            $shipping->shipping_charges = $data['shipping_charges'];
            $shipping->save();
            return redirect()->back()->with('flash_message_success', 'Shipping Charges Updated');

        }
        return view ('admin.shipping.edit_shipping', compact('shipping'));
    }
}
