<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        $productsAll = Product::orderBy('id', 'DESC')->where('status', '=', 1)->where('feature_item', '=', 1)->get();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $banners = Banner::where('status', '1')->get();
        return view ('index', compact('productsAll', 'categories', 'banners'));
    }
}
