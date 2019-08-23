@extends('layouts.adminLayout.admin_design')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Shipping</a> <a href="#" class="current">Edit Shipping</a> </div>
            <h1>Edit Shipping</h1>
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
        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Edit Shipping</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="" name="add_category" id="add_currency" novalidate="novalidate">
                                @csrf
                                <div class="control-group">
                                    <label class="control-label">Country</label>
                                    <div class="controls">
                                        <input type="text" name="country" id="country" value="{{$shipping->country}}">
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Charges</label>
                                    <div class="controls">
                                        <input type="text" name="shipping_charges" id="shipping_charges" value="{{$shipping->shipping_charges}}">
                                    </div>
                                </div>


                                <div class="form-actions">
                                    <input type="submit" value="Update Currency" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection