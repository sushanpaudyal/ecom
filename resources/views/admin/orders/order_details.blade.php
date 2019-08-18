@extends('layouts.adminLayout.admin_design')


@section('content')

    <!--main-container-part-->
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Orders</a> </div>
            <h1>Order # {{$orderDetails->id}}</h1>
        </div>

        @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! session('flash_message_success') !!}</strong>
            </div>
        @endif
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                            <h5>Order Details</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-striped table-bordered">

                                <tbody>
                                <tr>
                                    <td class="taskDesc">Order Date</td>
                                    <td class="taskStatus">{{$orderDetails->created_at}}</td>
                                </tr>
                                <tr>
                                    <td class="taskDesc">Order Status</td>
                                    <td class="taskStatus">{{$orderDetails->order_status}}</td>
                                </tr>

                                <tr>
                                    <td class="taskDesc">Order Total</td>
                                    <td class="taskStatus">{{$orderDetails->grand_total}}</td>
                                </tr>
                                <tr>
                                    <td class="taskDesc">Shipping Charges</td>
                                    <td class="taskStatus">{{$orderDetails->shipping_charges}}</td>
                                </tr>

                                <tr>
                                    <td class="taskDesc">Coupon Code</td>
                                    <td class="taskStatus">{{$orderDetails->coupon_code}}</td>
                                </tr>

                                <tr>
                                    <td class="taskDesc">Payment Method</td>
                                    <td class="taskStatus">{{$orderDetails->payment_method}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                            <h5>Billing Details</h5>
                        </div>
                        <div class="widget-content">
                            {{$userDetails->name}} <br>
                            {{$userDetails->address}} <br>
                            {{$userDetails->city}} <br>
                            {{$userDetails->state}} <br>
                            {{$userDetails->country}} <br>
                            {{$userDetails->mobile}} <br>

                        </div>
                    </div>


                </div>
                <div class="span6">

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                            <h5>Customer Details</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-striped table-bordered">

                                <tbody>
                                <tr>
                                    <td class="taskDesc">Customer Name</td>
                                    <td class="taskStatus">{{$orderDetails->name}}</td>
                                </tr>
                                <tr>
                                    <td class="taskDesc">Customer Email</td>
                                    <td class="taskStatus">{{$orderDetails->user_email}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                            <h5>Update Order Status</h5>
                        </div>
                        <div class="widget-content ">
                            <form action="{{route('updateOrderStatus')}}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$orderDetails->id}}">

                               <table width="100%">
                                   <tr>
                                       <td>
                                           <select name="order_status" id="order_status" class="control-label" required style="width: 200px">
                                               <option value="New" @if($orderDetails->order_status == "New") selected @endif>New</option>
                                               <option value="Pending"  @if($orderDetails->order_status == "Pending") selected @endif>Pending</option>
                                               <option value="In Process"  @if($orderDetails->order_status == "In Process") selected @endif>In Process</option>
                                               <option value="Shipped"  @if($orderDetails->order_status == "Shipped") selected @endif>Shipped</option>
                                               <option value="Cancelled"  @if($orderDetails->order_status == "Cancelled") selected @endif>Cancelled</option>
                                               <option value="Delivered"  @if($orderDetails->order_status == "Delivered") selected @endif>Delivered</option>
                                           </select>
                                       </td>
                                       <td>
                                           <button type="submit" class="btn btn-primary"> Update </button>
                                       </td>
                                   </tr>
                               </table>

                            </form>
                        </div>
                    </div>

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                            <h5>Shipping Details</h5>
                        </div>
                        <div class="widget-content">
                            {{$orderDetails->name}} <br>
                            {{$orderDetails->address}} <br>
                            {{$orderDetails->city}} <br>
                            {{$orderDetails->state}} <br>
                            {{$orderDetails->country}} <br>
                            {{$orderDetails->mobile}} <br>

                        </div>
                    </div>



                </div>
            </div>

        </div>



        <section id="do_action">
            <div class="container">
                <div class="heading" align="center">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product name</th>
                            <th>Product Size</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orderDetails->orders as $pro)
                            <tr>
                                <td>{{$pro->product_code}}</td>
                                <td>{{$pro->product_name}}</td>
                                <td>{{$pro->product_size}}</td>
                                <td>{{$pro->price}}</td>
                                <td>{{$pro->product_qty}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section><!--/#do_action-->
    </div>
    <!--main-container-part-->

    @endsection