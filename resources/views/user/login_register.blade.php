@extends('layouts.frontLayout.front_design')

@section('content')
    <section id="form" style="margin-top: 0px;"><!--form-->
        <div class="container">
            <div class="row">
                @if(Session::has('flash_message_error'))
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! session('flash_message_error') !!}</strong>
                    </div>
                @endif

                    @if(Session::has('flash_message_success'))
                        <div class="alert alert-warning alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{!! session('flash_message_success') !!}</strong>
                        </div>
                    @endif
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form"><!--login form-->
                        <h2>Login to your account</h2>
                        <form action="{{url('/user-login')}}" id="loginForm" name="loginForm" method="post">
                            @csrf
                            <input type="email" name="email" placeholder="Email Address" />
                            <input type="password" name="password" placeholder="Password" />

                            <button type="submit" class="btn btn-default">Login</button>
                            <br>
                            <a href="{{{url('/forget-password')}}}">Forget Password ?</a>
                        </form>
                    </div><!--/login form-->
                </div>
                <div class="col-sm-1">
                    <h2 class="or">OR</h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form"><!--sign up form-->
                        <h2>New User Signup!</h2>
                        <form action="{{route('loginregister')}}" id="registerForm" name="registerForm" method="post">
                            @csrf
                            <input type="text" placeholder="Name" id="name" name="name"/>
                            <input type="email" placeholder="Email Address" id="email" name="email"/>
                            <input type="password" placeholder="Password" id="myPassword" name="password"/>
                            <button type="submit" class="btn btn-default">Signup</button>
                        </form>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->
    @endsection