@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">View Products</a> </div>
            <h1> View Products</h1>
            @if(Session::has('flash_message_error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">X</button>
                    <strong class="text-danger">{!! session('flash_message_error') !!}</strong>
                </div>
            @endif

            @if(Session::has('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">X</button>
                    <strong class="text-success">{!! session('flash_message_success') !!}</strong>
                </div>
            @endif
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>Products</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Prodcut Code</th>
                                    <th>Product Color</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->category_name}}</td>
                                        <td>
                                            <img src="{{asset('/images/backend_images/products/small/'.$product->image)}}" alt="" style="width: 50px;">
                                        </td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->product_code}}</td>
                                        <td>{{$product->product_color}}</td>
                                        <td>{{$product->price}}</td>
                                        <td class="center">
                                            <a class="btn btn-success btn-mini" href="#myModal{{$product->id}}" data-toggle="modal">View</a>
                                            <a href="{{route('editProduct', $product->id)}}" class="btn btn-primary btn-mini">Edit</a>
                                            <a id="delCat" href="" class="btn btn-danger btn-mini delCat">Delete</a>
                                        </td>
                                    </tr>


                                    <div id="myModal{{$product->id}}" class="modal hide">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">×</button>
                                            <h3>{{$product->product_name}} Details</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Product ID : {{$product->id}}</p>
                                            <p>Product Name : {{$product->product_name}}</p>
                                            <p>Product Code : {{$product->product_code}}</p>
                                            <p>Product Price : {{$product->price}}</p>
                                            <p>Product Description : {{$product->description}}</p>


                                        </div>
                                    </div>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection