<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $category = new Category;
            $category->name = $data['category_name'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->save();
            return redirect()->route('viewCategories')->with('flash_message_success', 'Category Created Successfully');
        }
        return view ('admin.categories.add_category');
    }

    public function viewCategories(){
        $categories = Category::get();
        return view ('admin.categories.view-categories', compact('categories'));
    }
}
