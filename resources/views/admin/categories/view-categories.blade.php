@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Categories</a> <a href="#" class="current">View Categories</a> </div>
            <h1> View Categories</h1>
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
                            <h5>Data table</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Category URL</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                              <tbody>
                              @foreach($categories as $category)
                              <tr class="gradeX">
                                  <td>{{$loop->index +1}}</td>
                                  <td>{{$category->name}}</td>
                                  <td>{{$category->url}}</td>
                                  <td class="center">
                                      <a href="{{route('editCategory', $category->id)}}" class="btn btn-primary btn-mini">Edit</a>
                                      <a id="delCat" href="{{route('deleteCategory', $category->id)}}" class="btn btn-danger btn-mini delCat">Delete</a>

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