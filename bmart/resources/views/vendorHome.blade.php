@extends('layouts.app')
  
@section('content')
<div class="section-content">
    <div class="container-fluid">
        <table class="table">
            <thead>
                <tr>
                    <th><h2>Hello {{Auth::user()->name}}!</h2></th>
                    <th colspan="4"></th>
                    <th><a class="text-white btn btn-primary">Add Product</a></th>
                </tr>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Description</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->product_price}}</td>
                    <td>{{$product->category_name}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->description}}</td>
                    <td>
                        <a class="text-white btn btn-success">Edit</a>
                        <a class="text-white btn btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection