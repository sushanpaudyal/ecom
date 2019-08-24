@extends('layouts.adminLayout.admin_design')


@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">View Admins / Subadmins</a> </div>
            <h1>Admins</h1>
            @if(Session::has('flash_message_error'))
                <div class="alert alert-error alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{!! session('flash_message_error') !!}</strong>
                </div>
            @endif
            @if(Session::has('flash_message_success'))
                <div class="alert alert-su alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{!! session('flash_message_success') !!}</strong>
                </div>
            @endif
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>Admins / Sub Admins</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Updated On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>{{$loop->index +1}}</td>
                                        <td>{{$admin->username}}</td>
                                        <td>{{$admin->type}}</td>
                                        <td>
                                            @if($admin->status == 1)
                                                <span style="color: green;">Active</span>
                                                @else
                                                <span style="color: red;">InActive</span>
                                            @endif
                                        </td>
                                        <td>{{$admin->created_at}}</td>
                                        <td>{{$admin->updated_at}}</td>
                                        <td>
                                            <a href="" class="btn btn-primary btn-mini">Edit</a>
                                        </td>
                                    </tr>
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