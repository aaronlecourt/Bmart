@extends('layouts.app')
@section('title', 'Orders')
@section('content')
    <div class="d-flex container-fluid p-5 justify-content-center login-wrap">
        <div class="bg-light rounded-0 shadow-sm p-4 testform" style="border:2px solid #dedede;">
            <h4 style="font-weight:600; text-align:center;">Checkout Form</h4>
            <p class="px-3" style="text-align:center">Enter Billing Details and view the following order details before placing your order.</p>
            <fieldset class="form-group border p-3 col">
                <h5 style="font-weight:600">Billing Details</h5>
                <div class="form-group">
                    <label for="username">Name:</label>
                    <input type="text" class="form-control username" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="username">Address:</label>
                    <input type="text" class="form-control username" id="username" name="username">
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label for="email">City:</label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="col">
                        <label for="email">Country:</label>
                        <input type="text" class="form-control">
                      </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label for="email">Post Code:</label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="col">
                        <label for="email">Phone Number:</label>
                        <input type="text" class="form-control">
                      </div>
                </div>
                <div class="form-group">
                    <label for="username">Email Address:</label>
                    <input type="text" class="form-control username" id="username" name="username">
                </div>
            </fieldset>
            <br>
            <fieldset class="form-group border p-3 col">
                <h5 style="font-weight:600">Order Details</h5>
                <h6 style="font-weight:600">Total Cost:</h6>
            </fieldset>
            <br>
            
        </div>
    </div>
@endsection