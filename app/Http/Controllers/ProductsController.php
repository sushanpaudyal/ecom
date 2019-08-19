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

            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }
            $product->status = $status;

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

            if(empty($data['status'])){
                $status = 0;
            } else {
                $status = 1;
            }

            Product::where(['id' => $id])->update(['category_id' =>  $data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' =>$data['care'], 'status' => $status,  'price' => $data['price'], 'image' => $filename ]);
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
        echo $proAttr->price;
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
            $productsAll = Product::where('product_name', 'like', '%'.$search_product.'%')->orwhere('product_code',$search_product)->where('status',1)->get();
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

}

