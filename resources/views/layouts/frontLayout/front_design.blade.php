<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(!empty($meta_title))
             {{$meta_title}}
          @else
        Home | E-Shopper
      @endif
    </title>
    @if(!empty($meta_description))
        <meta name="description" content="{{$meta_description}}">
        @endif
    @if(!empty($meta_keywords))
        <meta name="keywords" content="{{$meta_keywords}}">
    @endif
    <link href="{{asset('css/frontend_css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/prettyPhoto.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/price-range.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/easyzoom.css')}}" rel="stylesheet">
    <link href="{{asset('css/frontend_css/responsive.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/frontend_css/passtrength.css')}}" />

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head><!--/head-->

<body>

@include('layouts.frontLayout.front_header')

@yield('content')


@include('layouts.frontLayout.front_footer')



<script src="{{asset('js/frontend_js/jquery.js')}}"></script>
<script src="{{asset('js/frontend_js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/frontend_js/jquery.scrollUp.min.js')}}"></script>
<script src="{{asset('js/frontend_js/price-range.js')}}"></script>
<script src="{{asset('js/frontend_js/jquery.prettyPhoto.js')}}"></script>
<script src="{{asset('js/frontend_js/easyzoom.js')}}"></script>
<script src="{{asset('js/frontend_js/jquery.passtrength.min.js')}}"></script>
<script src="{{asset('js/backend_js/jquery.validate.js')}}"></script>
<script src="{{asset('js/frontend_js/main.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>
</html>