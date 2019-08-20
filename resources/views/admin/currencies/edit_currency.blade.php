@extends('layouts.adminLayout.admin_design')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Currency</a> <a href="#" class="current">Edit Currency</a> </div>
            <h1>Edit Currency</h1>
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
                            <h5>Add Currency</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{route('edit-currency', $currency->id)}}" name="add_category" id="add_currency" novalidate="novalidate">
                                @csrf
                                <div class="control-group">
                                    <label class="control-label">Currency Code</label>
                                    <div class="controls">
                                        <input type="text" name="currency_code" id="currency_code" value="{{$currency->currency_code}}">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Exchnage Rate</label>
                                    <div class="controls">
                                        <input type="number" name="exchange_rate" id="exchange_rate" value="{{$currency->exchange_rate}}">
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Status Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" value="1" @if($currency->status == 1) checked @endif>
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