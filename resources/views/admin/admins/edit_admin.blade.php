@extends('layouts.adminLayout.admin_design')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Admins</a> <a href="#" class="current">Edit Admins</a> </div>
            <h1>Edit Admin</h1>
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
                            <h5>Edit Admins</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{route('editAdmin', $adminDetails->id)}}" name="add_category" id="add_category" novalidate="novalidate">
                                @csrf

                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    <div class="controls">
                                        <input type="text" name="type" id="type" readonly value="{{$adminDetails->type}}">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">User Name</label>
                                    <div class="controls">
                                        <input type="text" name="username" id="username" value="{{$adminDetails->username}}">
                                    </div>
                                </div>


                                @if($adminDetails->type == "SubAdmin")
                                    <div class="control-group" id="access">
                                        <label class="control-label">Access</label>
                                        <div class="controls">
                                            <input type="checkbox" name="categories_access" id="categories_access" value="1" style="margin-top: -3px;" @if($adminDetails->categories_access == 1) checked @endif> &nbsp Categories
                                            <input type="checkbox" name="product_access" id="product_access" value="1" style="margin-top: -3px;" @if($adminDetails->product_access == 1) checked @endif> &nbsp Product
                                            <input type="checkbox" name="orders_access" id="orders_access" value="1" style="margin-top: -3px;" @if($adminDetails->orders_access == 1) checked @endif> &nbsp Orders
                                            <input type="checkbox" name="users_access" id="users_access" value="1" style="margin-top: -3px;" @if($adminDetails->users_access == 1) checked @endif> &nbsp Users
                                        </div>
                                    </div>
                                    @endif


                                <div class="control-group">
                                    <label class="control-label">Status Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" value="1" @if($adminDetails->status == 1) checked @endif>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" value="Edit Admin" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection