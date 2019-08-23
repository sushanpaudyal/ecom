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
            $shipping->shipping_charges0_500g = $data['shipping_charges0_500g'];
            $shipping->shipping_charges501_1000g = $data['shipping_charges501_1000g'];
            $shipping->shipping_charges1001_2000g = $data['shipping_charges1001_2000g'];
            $shipping->shipping_charges2001_5000g = $data['shipping_charges2001_5000g'];

            $shipping->save();
            return redirect()->back()->with('flash_message_success', 'Shipping Charges Updated');

        }
        return view ('admin.shipping.edit_shipping', compact('shipping'));
    }
}
