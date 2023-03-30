@extends('layouts.app')
@section('title', 'Vendor Home')
@section('content')
<div class="section-content">
    <div class="container-fluid">
        <table id="productTable" class="table table-hover">
            <thead>
                <tr>
                    <th><h2>Hello {{Auth::user()->name}}!</h2></th>
                    <th colspan="4">
                        @if(session()->has('message'))
                        <div class="bg-success alert">
                            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                            {{ session()->get('message') }}
                        </div>
                        @endif
                    </th>
                    <th><a href="{{route('products.create')}}" class="text-white btn btn-primary rounded-pill w-100">Add Product</a></th>
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
                        <a href="{{route('products.edit', $product->prod_id)}}" class="text-white btn btn-success rounded-pill">Edit</a>
                       <form method="POST" action="{{url('vendor/products'.'/'.$product->prod_id)}}" style="display:inline;">
                            {{method_field('DELETE')}}
                            {{csrf_field()}}
                            <input type="submit" class="btn btn-danger rounded-pill" value="Delete" onclick="return confirm('Confirm Delete?')">
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{$products->links()}}</div>
    </div>
</div>
@endsection