<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        $productsAll = Product::orderBy('id', 'DESC')->where('status', '=', 1)->where('feature_item', '=', 1)->paginate(3);
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $banners = Banner::where('status', '1')->get();

        // Meta Tags
        $meta_title = "E-Shop Sample Website";
        $meta_description = "Online Shoppong Site For Everyone";
        $meta_keywords = "eshop website, online shopping";

        return view ('index', compact('productsAll', 'categories', 'banners', 'meta_title', 'meta_keywords', 'meta_description'));
    }
}
