@extends('layouts.frontLayout.front_design')

@section('content')

    <?php use App\Product; ?>

    <section>
        <div class="container">
            <div class="row">
                @if(Session::has('flash_message_error'))
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! session('flash_message_error') !!}</strong>
                    </div>
                @endif
                <div class="col-sm-3">
                    @include('layouts.frontLayout.front_sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="product-details"><!--product-details-->
                        <div class="col-sm-5">
                            <div class="view-product">
                                <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                    <a href="{{asset('images/backend_images/products/large/'.$productDetails->image)}}">
                                        <img style="width: 100%;" class="mainImage" src="{{asset('images/backend_images/products/medium/'.$productDetails->image)}}" alt="" />
                                    </a>
                                </div>
                                {{--<h3>ZOOM</h3>--}}
                            </div>
                            <div id="similar-product" class="carousel slide" data-ride="carousel">

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <div class="item active thumbnails">
                                        @foreach($productAltImages as $altImage)
                                            <a href="{{asset('images/backend_images/products/large/'.$altImage->image)}}">
                                                <img class="changeImage" src="{{asset('images/backend_images/products/small/'.$altImage->image)}}" alt="" style="width:80px;">
                                            </a>
                                        @endforeach
                                    </div>



                                </div>


                            </div>

                        </div>
                        <div class="col-sm-7">
                            <form action="{{url('add-cart')}}" name="addtocartForm" method="post" id="addtoCart" >
                                @csrf
                                <input type="hidden" name="product_id" value="{{$productDetails->id}}">
                                <input type="hidden" name="product_name" value="{{$productDetails->product_name}}">
                                <input type="hidden" name="product_code" value="{{$productDetails->product_code}}">
                                <input type="hidden" name="product_color" value="{{$productDetails->product_color}}">
                                <input type="hidden" id="price" name="price" value="{{$productDetails->price}}">
                            <div class="product-information"><!--/product-information-->
                                <img src="{{asset('images/backend_images/product-details/new.jpg')}}" class="newarrival" alt="" />
                                <h2>{{$productDetails->product_name}}</h2>
                                <p>Product Code: {{$productDetails->product_code}}</p>
                                @if(!empty($productDetails->sleeve))
                                    <p>Sleeve : {{$productDetails->sleeve}}</p>

                                @endif

                                <p>
                                    <select name="size" style="width: 150px;" id="selSize">
                                        <option disabled selected>Select Size</option>
                                        @foreach($productDetails->attributes as $sizes)
                                            <option value="{{$productDetails->id}}-{{$sizes->size}}">{{$sizes->size}}</option>
                                            @endforeach
                                    </select>
                                </p>

                                <img src="images/product-details/rating.png" alt="" />
                                <span>
                                    <?php $getCurrencyRates = Product::getCurrencyRates($productDetails->price); ?>
									<span id="getPrice">Rs. {{$productDetails->price}}
                                        <br>
                                          <h2>USD {{$getCurrencyRates['USD_Rate']}}</h2>
                                        <h2>GBP {{$getCurrencyRates['GBP_Rate']}}</h2>
                                          <h2>EURO {{$getCurrencyRates['EUR_Rate']}}</h2>

                                    </span>

									<label>Quantity:</label>
									<input type="text" value="1" name="quantity"/>
									@if($total_stock > 0)
                                        <button type="submit" class="btn btn-fefault cart" id="cartButton">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
                                        @endif
								</span>
                                <p><b>Availability:</b> <span id="availability">@if($total_stock > 0) In Stock @else Out of Stock @endif</span></p>

                                <p>
                                    <b>Delivery</b>
                                    <input type="text" name="pincode" id="chkPincode" placeholder="Check Pincode" >
                                    <button type="button" onclick="return checkPinCode()">Go</button>
                                    <span id="pincodeResponse"></span>
                                </p>

                            </div><!--/product-information-->
                            </form>
                        </div>
                    </div><!--/product-details-->

                    <div class="category-tab shop-details-tab"><!--category-tab-->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs">
                                <li><a href="#description" data-toggle="tab">Description</a></li>
                                <li><a href="#care" data-toggle="tab">Materials &amp; Care</a></li>
                                <li><a href="#delivery" data-toggle="tab">Delivery Option</a></li>
                                @if(!empty($productDetails->video))
                                <li><a href="#video" data-toggle="tab">Product Video</a></li>
                                    @endif

                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active in" id="description" >
                                <p>
                                    {{$productDetails->description}}
                                </p>
                            </div>

                            <div class="tab-pane fade" id="care" >
                                <p>
                                    {{$productDetails->care}}
                                </p>
                            </div>


                            <div class="tab-pane fade" id="delivery" > 
                                <div class="col-sm-12"> 
                                    <p>100% More Original Products <br> 
                                        Cash on Delivery</p> 
                                </div> 
                            </div>


                            @if(!empty($productDetails->video))
                                <div class="tab-pane fade" id="video" >
                                    <div class="col-sm-12">
                                        <video width="320" height="200" controls>
                                            <source src="{{asset('videos/'.$productDetails->video)}}" type="video/mp4">
                                            YOUR BROUSWER DOES NOT SUPPORT VIDEO TAG.
                                        </video>
                                    </div>
                                </div>
                                @endif

                        </div>
                    </div><!--/category-tab-->

                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">recommended items</h2>

                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php $count = 1; ?>
                                @foreach($relatedProducts->chunk(3) as $chunk)
                                    <div <?php if($count == 1){ ?> class="item active" <?php } else { ?> class="item" <?php } ?>>
                                        @foreach($chunk as $item)
                                            <div class="col-sm-4">
                                                <div class="product-image-wrapper">
                                                    <div class="single-products">
                                                        <div class="productinfo text-center">
                                                            <img src="{{asset('images/backend_images/products/small/'.$item->image)}}" alt="" />
                                                            <h2>Rs. {{$item->price}}</h2>
                                                            <p>{{$item->product_name}}</p>
                                                            <a href="{{route('product', $item->id)}}">
                                                                <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <?php $count++ ?>
                                @endforeach

                            </div>
                            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div><!--/recommended_items-->

                </div>
            </div>
        </div>
    </section>

    @endsection
