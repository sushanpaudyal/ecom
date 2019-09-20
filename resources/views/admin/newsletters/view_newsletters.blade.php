@extends('layouts.adminLayout.admin_design')


@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">View Newsletters Subscribers</a> </div>
            <h1>Newsletters Subscribers</h1>
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


        <div style="margin-left:20px; ">
            <a href="{{ url('/admin/export-newsletter-emails') }}" class="btn btn-primary btn-mini">Export</a>
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>Newsletters Subscribers</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($newsletterss as $newsletter)
                                    <tr>
                                        <td>{{$loop->index +1}}</td>
                                        <td>{{$newsletter->email}}</td>
                                        <td>
                                            @if($newsletter->status == 1)
                                                <a href="{{url('/admin/update-newsletter-status/'.$newsletter->id.'/0')}}">
                                                    <span style="color: green;">Active</span>
                                                </a>
                                                @else
                                                <a href="{{url('/admin/update-newsletter-status/'.$newsletter->id.'/1')}}">
                                                    <span style="color: red;">InActive</span>
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{$newsletter->created_at}}</td>
                                        <td>
                                            <a rel="{{$newsletter->id}}" rel1="delete-newsletter" href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
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