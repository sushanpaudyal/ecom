<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductsAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;

class ProductsController extends Controller
{
    public function addProduct(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            $product = new Product;

            if(empty($data['category_id'])){
                return redirect()->back()->with('flash_message_error', 'Under Category is Missing');
            }
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if(!empty($data['description'])){
                $product->description = $data['description'];
            } else {
                $product->description = "";
            }

            if(!empty($data['care'])){
                $product->care = $data['care'];
            } else {
                $product->care = "";
            }

            $product->price = $data['price'];
            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'. $filename;
                    $medium_image_path = 'images/backend_images/products/medium/'. $filename;
                    $small_image_path = 'images/backend_images/products/small/'. $filename;
                    //Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    // Store Image
                    $product->image = $filename;
                }
            }

            $product->save();
            return redirect()->route('viewProducts')->with('flash_message_success', 'Product Created Successfully');
        }

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option selected disabled > Select </option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'> ".$cat->name." </option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value='".$sub_cat->id."'>  &nbsp; &nbsp; --- ".$sub_cat->name."  </option>";
            }
        }
        return view ('admin.products.add-product', compact('categories_dropdown'));
    }

    public function viewProducts(){
        $products = Product::get();
        foreach($products as $key => $val){
          $category_name = Category::where(['id' => $val->category_id])->first();
          $products[$key]->category_name = $category_name->name;
        }
        return view ('admin.products.view_products', compact('products'));
    }

    public function editProduct(Request $request, $id){

        if($request->isMethod('post')){
            $data = $request->all();

            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'. $filename;
                    $medium_image_path = 'images/backend_images/products/medium/'. $filename;
                    $small_image_path = 'images/backend_images/products/small/'. $filename;
                    //Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
            } else {
                $filename = $data['current_image'];
            }

            if(empty($data['description'])){
                $data['description'] = "";
            }

            if(empty($data['care'])){
                $data['care'] = "";
            }

            Product::where(['id' => $id])->update(['category_id' =>  $data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' =>$data['care'], 'price' => $data['price'], 'image' => $filename ]);
            return redirect()->back()->with('flash_message_success', 'Product Updated Successfully');

        }

        $productDetails = Product::where(['id' => $id])->first();
        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option selected disabled > Select </option>";
        foreach($categories as $cat){
            if($cat->id == $productDetails->category_id){
                $selected = "selected";
            } else {
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected."> ".$cat->name." </option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach($sub_categories as $sub_cat){
                if($sub_cat->id == $productDetails->category_id){
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $categories_dropdown .= "<option value='".$sub_cat->id."' ".$selected.">  &nbsp; &nbsp; --- ".$sub_cat->name."  </option>";
            }
        }
        return view ('admin.products.edit_product', compact('productDetails', 'categories_dropdown'));
    }

    public function deleteProductImage($id = null){
        $productImage = Product::where(['id' => $id])->first();
        // Get Image Path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }

        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        Product::where(['id' => $id])->update(['image' => '']);
        return redirect()->back()->with('flash_message_success', 'Product Image Deleted Successfully');
    }

    public function deleteProduct($id){
        Product::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product Deleted Successfully');
    }

    public function addAttributes(Request $request, $id){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        if($request->isMethod('post')){
            $data = $request->all();
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }
            return redirect()->back()->with('flash_message_success', 'Attribute added successfully');
        }
        return view ('admin.products.add_attributes', compact('productDetails'));
    }

    public function deleteAttribute($id = null)
    {
        ProductsAttribute::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Attribute Delete successfully');
    }


    public function products($url = null)
    {
        $countCategory = Category::where(['url' => $url])->count();
        if($countCategory == 0){
            abort(404);
        }

        // Getting all categories and sub categories 
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $categoryDetails = Category::where(['url' => $url])->first();
        if ($categoryDetails->parent_id == 0) {
            // if url is main category 
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach ($subCategories as $subcat) {
                $cat_ids[] = $subcat->id;
            }
            $productsAll = Product::whereIn('category_id', $cat_ids)->get();
            $productsAll = json_decode(json_encode($productsAll));
        } else {
            // if url is subcategory url 
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
        }
        return view('products.listing')->with(compact('categoryDetails', 'productsAll', 'categories'));
    }

    public function product($id){
        $productDetails = Product::with('attributes')->where('id', $id)->first();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view ('products.details', compact('productDetails', 'categories'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        $proArr = explode("-", $data['idSize']);
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        echo $proAttr->price;

    }

}

