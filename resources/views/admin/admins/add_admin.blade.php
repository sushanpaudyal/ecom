@extends('layouts.adminLayout.admin_design')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Admins</a> <a href="#" class="current">Add Admins</a> </div>
            <h1>Add Admin</h1>
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
                            <h5>Add Admins</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="" name="add_category" id="add_category" novalidate="novalidate">
                                @csrf

                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    <div class="controls">
                                        <select name="type" id="type" style="width: 220px;">
                                            <option value="Admin">Admin</option>
                                            <option value="SubAdmin">Sub Admin</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">User Name</label>
                                    <div class="controls">
                                        <input type="text" name="username" id="username">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Password</label>
                                    <div class="controls">
                                        <input type="password" name="password" id="password">
                                    </div>
                                </div>

                                <div class="control-group" id="access">
                                    <label class="control-label">Access</label>
                                    <div class="controls">
                                        <input type="checkbox" name="categories_access" id="categories_access" value="1" style="margin-top: -3px;"> &nbsp Categories
                                        <input type="checkbox" name="product_access" id="product_access" value="1" style="margin-top: -3px;"> &nbsp Product
                                        <input type="checkbox" name="orders_access" id="orders_access" value="1" style="margin-top: -3px;"> &nbsp Orders
                                        <input type="checkbox" name="users_access" id="users_access" value="1" style="margin-top: -3px;"> &nbsp Users
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Status Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" value="1">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" value="Add Admin" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection