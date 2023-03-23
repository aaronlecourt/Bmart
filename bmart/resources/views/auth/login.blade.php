@extends('layouts.app')

@section('content')
<div class="container-fluid login-wrap">
    <div class="testform">
        <h4>Login Your Account</h4>
        <hr>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input id="email" placeholder="Your email" type="email" class="form-control mt-3 login-name @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            
            <input id="password" placeholder="Your Password" type="password" class="form-control mt-3 login-pass @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
