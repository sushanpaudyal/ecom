@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">CMS Page</a> <a href="#" class="current">View CMS Pages</a> </div>
            <h1> View CMS Pages</h1>
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
                            <h5>CMS Pages</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Page ID</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cmsPages as $cms)
                                    <tr>
                                        <td>{{$cms->id}}</td>
                                        <td>{{$cms->title}}</td>
                                        <td>{{$cms->url}}</td>
                                        <td>
                                            @if($cms->status == 1) Active @else In Active @endif
                                         </td>
                                        <td>{{$cms->created_at}}</td>
                                        <td>
                                            <a class="btn btn-success btn-mini" href="#myModal{{$cms->id}}" data-toggle="modal">View</a>
                                            <a href="{{route('edit-cms-page', $cms->id)}}" class="btn btn-primary btn-mini">Edit</a>
                                            <a rel="{{$cms->id}}" rel1="delete-cms-page" href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                                        </td>
                                    </tr>


                                    <div id="myModal{{$cms->id}}" class="modal hide">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">×</button>
                                            <h3>{{$cms->title}} Details</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Product Description : {{$cms->description}}</p>
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