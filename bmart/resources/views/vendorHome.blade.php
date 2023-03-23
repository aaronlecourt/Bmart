@extends('layouts.app')
  
@section('content')
<div class="section-content">
    <div class="container-fluid">
            <h2>Hello {{Auth::user()->name}}!</h2>
        <hr>
        <table class="table">
            <thead>
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
                        <button type="submit" class="text-white" style="background-color:rgb(0, 159, 0);">Edit</button>
                        <button type="submit" class="text-white" style="background-color:rgb(255, 42, 42);">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection