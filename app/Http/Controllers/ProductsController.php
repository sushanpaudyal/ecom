<?php

namespace App\Http\Controllers;

use App\Category;
use App\Coupon;
use App\DeliveryAddress;
use App\Order;
use App\OrdersProduct;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Image;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;


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

            // Uploading Video
            if($request->hasFile('video')){
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $product->video = $video_name;
            }


            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }


            if(empty($data['sleeve'])){
                $product->sleeve = "";
            } else {
                $product->sleeve = $data['sleeve'];

            }


            $product->status = $status;

            if(empty($data['feature_item'])){
                $feature = 0;
            } else {
                $feature = 1;
            }
            $product->feature_item = $feature;

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

        $sleeveArray = array('Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless');
        return view ('admin.products.add-product', compact('categories_dropdown', 'sleeveArray'));
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


            // Uploading Video
            if($request->hasFile('video')){
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $videoName = $video_name;
            } else if(!empty($data['current_video'])){
                $videoName = $data['current_video'];
            } else {
                $videoName = '';
            }


            if(empty($data['description'])){
                $data['description'] = "";
            }

            if(empty($data['care'])){
                $data['care'] = "";
            }

            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }

            if(empty($data['sleeve'])){
                $sleeve = "";
            } else {
                $sleeve = $data['sleeve'];
            }

            if(empty($data['feature_item'])){
                $feature = 0;
            } else {
                $feature = 1;
            }
            Product::where(['id' => $id])->update(['category_id' =>  $data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' =>$data['care'], 'status' => $status, 'feature_item' => $feature,  'price' => $data['price'], 'image' => $filename , 'video' => $videoName, 'sleeve' => $sleeve]);
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
        $sleeveArray = array('Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless');

        return view ('admin.products.edit_product', compact('productDetails', 'categories_dropdown', 'sleeveArray'));
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

    public function deleteProductVideo($id){
        $productVideo = Product::select('video')->where('id', $id)->first();
        $video_path = 'videos/';

        if(file_exists($video_path.$productVideo->video)){
            unlink($video_path.$productVideo->video);
        }

        Product::where('id', $id)->update(['video' => '']);
        return redirect()->back()->with('flash_message_success', 'Product Video Deleted Successfully');
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
                    // SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku', $val)->count();
                    if($attrCountSKU > 0){
                        return redirect()->back()->with('flash_message_error', 'SKU Already Exits');
                    }

                    // Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if($attrCountSizes > 0){
                        return redirect()->back()->with('flash_message_error', 'Size Already Exits');
                    }
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

    public function editAttributes(Request $request, $id = null){
        if($request->isMethod('post')){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;

            foreach($data['idAttr'] as $key => $attr){
                ProductsAttribute::where(['id' => $data['idAttr'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
            }
            return redirect()->back()->with('flash_message_success', 'Products Attributes Updated Successfully');
        }
    }

    public function deleteAttribute($id = null)
    {
        ProductsAttribute::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Attribute Delete successfully');
    }

    public function products($url = null)
    {
        // Show 404 Page if Category does not exists
        $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
        if($categoryCount==0){
            abort(404);
        }
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url'=>$url])->first();
        if($categoryDetails->parent_id==0){
            $subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
            $subCategories = json_decode(json_encode($subCategories));
            foreach($subCategories as $subcat){
                $cat_ids[] = $subcat->id;
            }
            $productsAll = Product::whereIn('products.category_id', $cat_ids)->where('products.status','1')->orderBy('products.id','Desc');
            $breadcrumb = "<a href='/'> Home </a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";

        }else{
            $productsAll = Product::where(['products.category_id'=>$categoryDetails->id])->where('products.status','1')->orderBy('products.id','Desc');
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'> Home </a> / <a href='".$mainCategory->url."'>".$mainCategory->name."</a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
        }
        if(!empty($_GET['color'])){
            $colorArray = explode('-',$_GET['color']);
            $productsAll = $productsAll->whereIn('products.product_color',$colorArray);
        }
        if(!empty($_GET['sleeve'])){
            $sleeveArray = explode('-',$_GET['sleeve']);
            $productsAll = $productsAll->whereIn('products.sleeve',$sleeveArray);
        }


        if(!empty($_GET['size'])){
            $sizeArray = explode('-',$_GET['size']);
            $productsAll = $productsAll->join('products_attributes','products_attributes.product_id','=','products.id')
                ->select('products.*','products_attributes.product_id','products_attributes.size')
                ->groupBy('products_attributes.product_id')
                ->whereIn('products_attributes.size',$sizeArray);
        }



//        $colorArray = array('Black', 'Blue', 'Brown', 'Gold','Green', 'Orange', 'Pink', 'Purple', 'Red', 'Yellow', 'Silver', 'White');
        $colorArray = Product::select('product_color')->groupBy('product_color')->get();
        $colorArray = array_flatten(json_decode(json_encode($colorArray), true));

        $sleeveArray = Product::select('sleeve')->where('sleeve', '!=', '')->groupBy('sleeve')->get();
        $sleeveArray = array_flatten(json_decode(json_encode($sleeveArray), true));

        $sizesArray = ProductsAttribute::select('size')->groupBy('size')->get();
        $sizesArray = array_flatten(json_decode(json_encode($sizesArray), true));

        $productsAll = $productsAll->paginate(6);



        return view('products.listing')->with(compact('categoryDetails', 'productsAll', 'categories', 'url', 'colorArray', 'sleeveArray', 'sizesArray', 'breadcrumb'));
    }

    public function product($id){

        //        Show 404 Page if product is disabled
        $productsCount = Product::where(['id' => $id, 'status' => 1])->count();
        if($productsCount ==0){
            abort(404);
        }

        $productDetails = Product::with('attributes')->where('id', $id)->first();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id' => $productDetails->category_id])->get();

        $productAltImages = ProductsImage::where(['product_id' => $id])->get();
        $total_stock = ProductsAttribute::where('product_id', $id)->sum('stock');
        return view ('products.details', compact('productDetails', 'categories', 'productAltImages', 'total_stock', 'relatedProducts'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        $proArr = explode("-", $data['idSize']);
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        $getCurrencyRates = Product::getCurrencyRates($proAttr->price);
        echo $proAttr->price."-".$getCurrencyRates['USD_Rate']."-".$getCurrencyRates['GBP_Rate']."-".$getCurrencyRates['EUR_Rate'];
        echo "#";
        echo $proAttr->stock;
    }

    public function addImages(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        if($request->isMethod('post')){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach($files as $file){
                    $image = new ProductsImage();
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;

                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }

            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success', 'Images Hass Been Added Successfully');
        }
        $productsImages = ProductsImage::where(['product_id' => $id])->get();
        return view ('admin.products.add_images', compact('productDetails', 'productsImages'));
    }

    public function deleteAltImage($id = null){
        $productImage = ProductsImage::where(['id' => $id])->first();


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

        ProductsImage::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product Alternate Image has been Deleted successfully');
    }

    public function addToCart(Request $request){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        $data = $request->all();
        if(empty(Auth::user()->email)){
            $data['user_email'] = "";
        } else {
            $data['user_email'] = Auth::user()->email;
        }

        // Check product stock is available or not
        $product_size = explode("-", $data['size']);
        $getProductStock = ProductsAttribute::where(['product_id' => $data['product_id'], 'size' => $product_size[1]])->first();
        if($getProductStock->stock < $data['quantity']){
            return redirect()->back()->with('flash_message_error', 'Required quantity is out of stock');
        }


        $session_id = Session::get('session_id');
        if(empty($session_id)){
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }

        $sizeIDArr = explode("-", $data['size']);
        $product_size = $sizeIDArr[1];

        if(empty(Auth::check())){
            $countProducts = DB::table('carts')->where(['product_id' => $data['product_id'],'product_color' => $data['product_color'],'size' => $product_size,'session_id' => $session_id])->count();
            if($countProducts>0){
                return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
            }
        }else{
            $countProducts = DB::table('carts')->where(['product_id' => $data['product_id'],'product_color' => $data['product_color'],'size' => $product_size,'user_email' => $data['user_email']])->count();
            if($countProducts>0){
                return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
            }
        }

        $getSKU = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'], 'size' => $product_size])->first();

        DB::table('carts')
            ->insert(['product_id' => $data['product_id'],'product_name' => $data['product_name'],
                'product_code' => $getSKU['sku'],'product_color' => $data['product_color'],
                'price' => $data['price'],'size' => $product_size,'quantity' => $data['quantity'],'user_email' => $data['user_email'],'session_id' => $session_id]);
        return redirect('cart')->with('flash_message_success','Product has been added in Cart!');
    }

    public function cart(){

        if(Auth::check()){
            $user_email = Auth::user()->email;
            $userCart = DB::table('carts')->where(['user_email' => $user_email])->get();
        } else {
            $sesion_id = Session::get('session_id');
            $userCart = DB::table('carts')->where(['session_id' => $sesion_id])->get();
        }


        foreach($userCart as $key => $product){
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }

        return view ('products.cart', compact('userCart'));
    }

    public function deleteCartProduct($id = null){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        DB::table('carts')->where('id', $id)->delete();
        return redirect()->route('viewCart')->with('flash_message_error', 'Cart Item Deleted Successfully');
    }

    public function updateCartQuantity($id = null, $quantity = null){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        $getCartDetails = DB::table('carts')->where('id', $id)->first();
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();

        $updated_quantity = $getCartDetails->quantity+$quantity;
        if($getAttributeStock->stock >= $updated_quantity){
            DB::table('carts')->where('id', $id)->increment('quantity', $quantity);
            return redirect('cart')->with('flash_message_success', 'Product Has Quantity has Been Updated');
        } else {
            return redirect('cart')->with('flash_message_error', 'Product Required Quantity is Out of Stock');
        }
    }

    public function applyCoupon(Request $request){

        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();
        if($couponCount == 0){
            return redirect()->back()->with('flash_message_error', 'Coupon Does Not Exists');
        } else {
            $couponDetails = Coupon::where('coupon_code', $data['coupon_code'])->first();
            // if coupon is inactive
            if($couponDetails->status == 0){
                return redirect()->back()->with('flash_message_error', 'This Coupon is Not Active');
            }

            // if coupon is expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if($expiry_date < $current_date){
                return redirect()->back()->with('flash_message_error', 'This Coupon Has Expired');
            }

            //After The coupon is valid for dicount check if amount type or fixed or percentage
            //getting total amount from cart

            $session_id = Session::get('session_id');

            if(Auth::check()){
                $user_email = Auth::user()->email;
                $userCart = DB::table('carts')->where(['user_email' => $user_email])->get();
            } else {
                $sesion_id = Session::get('session_id');
                $userCart = DB::table('carts')->where(['session_id' => $sesion_id])->get();
            }

            $total_amount = 0;
            foreach($userCart as  $item){
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }


            if($couponDetails->amount_type == "Fixed"){
                $couponAmount = $couponDetails->amount;
            } else {
                $couponAmount = $total_amount * ($couponDetails->amount / 100);
            }

            // Add Coupon Code and Amount in session
            Session::put('CouponAmount', $couponAmount);
            Session::put('CouponCode', $data['coupon_code']);
            return redirect()->back()->with('flash_message_success', 'Coupon Code Successfully Applied');

        }
    }

    public function checkout(Request $request){
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $user_email = Auth::user()->email;

        // check if shipping address exists

        $shippingCount = DeliveryAddress::where('user_id', $user_id)->count();
        if($shippingCount > 0){
            $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();
        }

        //update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('carts')->where(['session_id' => $session_id])->update(['user_email' => $user_email ]);


        if($request->isMethod('post')){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;

//            return to checkout page if any of the field is empty
            if(empty($data['billing_name']) || empty($data['billing_address']) || empty($data['billing_city']) || empty($data['billing_state']) || empty($data['billing_country']) || empty($data['billing_pincode']) || empty($data['billing_mobile']) || empty($data['shipping_name']) || empty($data['shipping_address'])  || empty($data['shipping_city'])){
                return redirect()->back()->with('flash_message_error', 'Please Fill all the fields to continue');
            }

            // update user details
            User::where('id', $user_id)->update(['name' => $data['billing_name'], 'address' => $data['billing_address'] , 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'country' => $data['billing_country'], 'pincode' => $data['billing_pincode'], 'mobile' => $data['billing_mobile']
            ]);

//            die;

            if($shippingCount > 0){
                // update shipping address
                DeliveryAddress::where('user_id', $user_id)->update(['name' => $data['shipping_name'], 'address' => $data['shipping_address'] , 'city' => $data['shipping_city'], 'state' => $data['shipping_state'], 'country' => $data['shipping_country'], 'pincode' => $data['shipping_pincode'], 'mobile' => $data['shipping_mobile']
                ]);
            } else {
                // add new shipping address
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = Auth::user()->email;
                $shipping->name = $data['shipping_name'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->country = $data['shipping_country'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->mobile = $data['shipping_mobile'];
                $shipping->save();
            }


            // checking pincodes
            $pincodeCount = DB::table('pincodes')->where('pincode', $data['shipping_pincode'])->count();
            if($pincodeCount == 0){
                return redirect()->back()->with('flash_message_error', 'Your location is not available for the delivery. Please choose another pincode location)');
            }

            return redirect('/order-review');
        }

        return view ('products.checkout', compact('userDetails', 'shippingDetails'));
    }


    public function orderReview(Request $request){
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $user_email = Auth::user()->email;
        $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();

        $userCart = DB::table('carts')->where(['user_email' => $user_email])->get();

        foreach($userCart as $key => $product){
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }

        return view ('products.order_review', compact('userDetails', 'shippingDetails' , 'userCart'));
    }


    public function placeOrder(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;
            //Getting the shipping details of the user
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();


            // checking pincodes
            $pincodeCount = DB::table('pincodes')->where('pincode', $shippingDetails->pincode)->count();
            if($pincodeCount == 0){
                return redirect()->back()->with('flash_message_error', 'Your location is not available for the delivery. Please choose another pincode location)');
            }


            if(empty(Session::get('CouponCode'))){
                $coupon_code = 0;
            } else {
                $coupon_code = Session::get('CouponCode');
            }

            if(empty(Session::get('CouponAmount'))){
                $coupon_amount = 0;
            } else {
                $coupon_amount = Session::get('CouponAmount');
            }

            if(empty($data['shipping_charges'])){
                $data['shipping_charges'] = 0;
            }


            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;

            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincodee = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->shipping_charges = $data['shipping_charges'];
            $order->grand_total = $data['grand_total'];
            $order->save();


            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('carts')->where(['user_email' => $user_email])->get();
            foreach($cartProducts as $pro){
                $cartPro = new OrdersProduct();
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_size = $pro->size;
                $cartPro->price = $pro->price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();
            }

            Session::put('order_id', $order_id);
            Session::put('grand_total', $data['grand_total']);


            // Sending Order Email

            $productDetails = Order::with('orders')->where('id', $order_id)->first();
            $userDetails = User::where('id', $user_id)->first();
            $email = $user_email;
            $messageData = [
              'email' => $email,
              'name'  => $shippingDetails->name,
                'order_id' => $order_id,
                'productDetails' => $productDetails,
                'userDetails' => $userDetails
            ];
            Mail::send('email.order', $messageData, function($message) use ($email){
                $message->to($email)->subject('Order Placed - E-Commerce Website');
            });

            return redirect()->route('thanks');

        }
    }


    public function thanks(){
        $user_email = Auth::user()->email;
        DB::table('carts')->where('user_email', $user_email)->delete();
        return view ('products.thanks');
    }


    public function userOrders(){
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id', $user_id)->get();

        return view ('products.users_orders', compact('orders'));
    }

    public function userOrderDetails($order_id){
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('user_id', $user_id)->first();
        return view ('products.user_order_details', compact('orderDetails'));
    }



    public function viewOrders(){
        $orders = Order::with('orders')->latest()->get();
        return view ('admin.orders.view_orders', compact('orders'));
    }

    public function viewOrderDetails($order_id){
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));

        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        return view ('admin.orders.order_details', compact('orderDetails', 'userDetails'));
    }

    public function updateOrderStatus(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            Order::where('id', $data['order_id'])->update(['order_status' => $data['order_status']]);
            return redirect()->back()->with('flash_message_success', 'Product Status Has Been Updated');
        }
    }

    public function searchProducts(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product =  $data['product'];
//            $productsAll = Product::where('product_name', 'like', '%'.$search_product.'%')->orwhere('product_code',$search_product)->where('status',1)->get();
            $productsAll = Product::where(function($query) use ($search_product){
                $query->where('product_name', 'like', '%'.$search_product.'%')
                    ->orWhere('description', 'like', '%'.$search_product.'%')
                    ->orWhere('product_code', 'like', '%'.$search_product.'%')
                    ->orWhere('product_color', 'like', '%'.$search_product.'%')

                ;
            })->where('status', 1)->get();
            return view ('products.listing', compact('categories', 'productsAll', 'search_product'));
        }
    }


    public function viewOrderInvoice($order_id){
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));

        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        return view ('admin.orders.order_invoice', compact('orderDetails', 'userDetails'));
    }

    public function checkPinCode(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;
            $pincodeCount = DB::table('pincodes')->where('pincode', $data['pincode'])->count();
            if($pincodeCount > 0){
                echo "This Pincode is available for Delivery";
            } else {
                echo "Delivery Not Available in this Pincode";
            }
        }
    }


    public function filter(Request $request){
        $data = $request->all();

        $colorUrl = "";
        if(!empty($data['colorFilter'])){
            foreach($data['colorFilter'] as $color){
                if(empty($colorUrl)){
                    $colorUrl = "&color=".$color;
                } else {
                    $colorUrl .= "-".$color;
                }
            }
        }

        $sleeveUrl = "";
        if(!empty($data['sleeveFilter'])){
            foreach($data['sleeveFilter'] as $sleeve){
                if(empty($sleeveUrl)){
                    $sleeveUrl = "&sleeve=".$sleeve;
                } else {
                    $sleeveUrl .= "-".$sleeve;
                }
            }
        }

        $sizeUrl = "";
        if(!empty($data['sizeFilter'])){
            foreach($data['sizeFilter'] as $size){
                if(empty($sizeUrl)){
                    $sizeUrl = "&size=".$size;
                } else {
                    $sizeUrl .= "-".$size;
                }
            }
        }

        $finalUrl = "products/".$data['url']."?".$colorUrl.$sleeveUrl.$sizeUrl;
        return redirect::to($finalUrl);
    }



}

