@extends('layouts.app2')
@section('title', 'Vendor Home')
@section('content')
<div class="container-fluid p-2 w-100 justify-content-center login-wrap">
    <div class="row bg-light rounded-0 shadow-sm testform" style="border:2px solid #dedede; width:600px;">
        @if(session()->has('message'))
        <div class="bg-success alert rounded-3">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            {{ session()->get('message') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="bg-danger alert rounded-3">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            {{ session()->get('error') }}
        </div>
        @endif
    <div class="col-lg-12">
        <h3 style="font-weight:600;">{{Auth()->user()->name}}'s Profile</h3>
        <h6>You can edit your profile and password here!</h6>
        
    </div>
        <div class="col-lg-6 col-md-12 py-3">
        <h5>Edit Profile</h5>
            <form action="{{route('update-profile')}}" method="POST">
                @csrf
                <input id="user_name" placeholder="User Name" type="text" class="form-control mt-3 @error('user_name') is-invalid @enderror" name="user_name" value="{{auth()->user()->name}}" autofocus>
                    @error('user_name')
                        <span class="text-danger" style="font-weight: 600; font-size: 10px">{{$message}}</span>
                    @enderror
                <input id="user_number" placeholder="Contact Number" type="text" class="form-control mt-3 @error('user_number') is-invalid @enderror" name="user_number" value="{{auth()->user()->number}}" autofocus>
                    @error('user_number')
                        <span class="text-danger" style="font-weight: 600; font-size: 10px">{{$message}}</span>
                    @enderror
                <input id="user_email" placeholder="Email" type="text" class="form-control mt-3 @error('user_email') is-invalid @enderror" name="user_email" value="{{auth()->user()->email}}" autofocus>
                    @error('user_email')
                        <span class="text-danger" style="font-weight: 600; font-size: 10px">{{$message}}</span>
                    @enderror
                <input id="user_address" placeholder="address" type="text" class="form-control mt-3 @error('user_address') is-invalid @enderror" name="user_email" value="{{auth()->user()->address}}" autofocus>
                    @error('user_address')
                        <span class="text-danger" style="font-weight: 600; font-size: 10px">{{$message}}</span>
                    @enderror
                <br>
                <button type="submit" class="btn btn-primary w-100" name="profileEdit">Edit Profile</button>
            </form>
        </div>
        <div class="col-lg-6 col-md-12 py-3">
        <h5>Change Password</h5>
            <form action="{{route('update-profile')}}" method="POST">
                @csrf
                <input id="new_password" placeholder="New Password" type="password" class="form-control mt-3 @error('new_password') is-invalid @enderror" name="new_password" value="" >
                    @error('new_password')
                        <span class="text-danger" style="font-weight: 600; font-size:10px;">{{$message}}</span>
                    @enderror
                <input id="new_password_confirmation" placeholder="Confirm Password" type="password" class="form-control mt-3 @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" value="" >
                    @error('new_password_confirmation')
                        <span class="text-danger" style="font-weight: 600; font-size:10px;">{{$message}}</span>
                    @enderror
                    <input id="old_password" placeholder="Old Password" type="password" class="form-control mt-3 @error('old_password') is-invalid @enderror" name="old_password" value="">
                        @error('old_password')
                            <span class="text-danger" style="font-weight: 600; font-size:10px;">{{$message}}</span>
                        @enderror
                <br>
                <button type="submit" class="btn btn-success w-100" name="passwordEdit">Edit Password</button>
            </form>
        </div>
    </div>
</div>
@endsection