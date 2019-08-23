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
}
