@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container-fluid login-wrap">
    <div class="testform">
        <h4>Login Your Account</h4>
        <hr>
        <form method="post" action="{{ route('login') }}">
            @csrf
            @if($errors->any())
            <div class="bg-danger alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                {{$errors->first()}}
            </div>
            @endif
            <input id="email" placeholder="Your email" type="email" class="form-control mt-3 login-name @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>            
            <input id="password" placeholder="Your Password" type="password" class="form-control mt-3 login-pass @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
            <br>
            <button type="submit" class="btn btn-warning w-100 mt-3 login-btn" id="loginbtn" >
                {{ __('Login') }}
            </button>            
            <br><br>
            <span>Don't have an account? <a href="/register">Sign up Now!</a></span>
        </form>
    </div>
</div>
@endsection
