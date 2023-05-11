@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="container-fluid login-wrap">
    <div class="testform">
        @if(session()->has('error'))
        <div class="bg-danger alert rounded-3">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            {{ session()->get('error') }}
        </div>
        @endif
        <h4>Register an Account</h4>
        <hr>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input id="name" placeholder="Enter name" type="text" class="form-control mt-3 register-name @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <h6>{{ $message }}</h6>
                                    </span>
                                @enderror
            <input id="email" placeholder="Enter email" type="email" class="form-control mt-3 register-email @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <h6>{{ $message }}</h6>
                                    </span>
                                @enderror
            <input id="password" placeholder="Enter password" type="password" class="form-control mt-3 register-pass @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <h6>{{ $message }}</h6>
                                    </span>
                                @enderror
            <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control mt-3 register-confpass" name="password_confirmation" required autocomplete="new-password">
            <br>
                <div class="radio-inputs">
                    <input type="radio" class="form-radio @error('isVendor') is-invalid @enderror" name="isVendor" id="isVendor" value="0"> I am a customer
                </div>
                <div class="radio-inputs">
                    <input type="radio" class="form-radio @error('isVendor') is-invalid @enderror"  name="isVendor" id="isVendor" value="1"> I am a vendor
                </div>
                @error('isVendor')
                    <span class="invalid-feedback" role="alert">
                        <h6>{{ $message }}</h6>
                    </span>
                @enderror
            <br>
            <p style="font-size:10pt;">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.</p>
                {{-- <div class="form-check checkterms">
                    <input class="form-check-input" type="checkbox" value="1" id="termsCond"> I agree with the Terms & Conditions.
                </div> --}}
            {{-- <br> --}}
            <button type="submit" class="btn btn-warning w-100 mt-3 regist-btn">
                {{ __('Register') }}
            </button>
            <br><br>
                <span>Already have an account? <a href="/">Login</a></span>
        </form>
    </div>
</div>
@endsection
