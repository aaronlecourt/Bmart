@extends('layouts.app')
@section('title', 'Edit Products')
@section('content')
    <div class="d-flex container-fluid p-5 justify-content-center login-wrap">
        <div class="bg-light rounded-0 shadow-sm p-4 w-50 testform" style="border:2px solid #dedede;">
            <h3>Edit Product</h3>
            <small>Currently editing product: {{$product->product_name}}</small>
            <hr>

            <form action="{{route('products.update', $product->id)}}" method="POST">
                @csrf
                @method('PUT')
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="bg-danger alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <ul>
                            <li>{{ $error }}</li>
                    </ul>
                </div>
                @endforeach
                @endif
                {{-- <input id="user_id" class="form-control" type="text" value="{{Auth::id()}}" name="user_id" readonly> --}}
                <input id="product_name" placeholder="Product Name" type="text" class="form-control mt-3" name="product_name" value="{{$product->product_name}}" required autofocus>
                <input id="product_price" placeholder="Product Price" type="number" class="form-control mt-3" name="product_price" value="{{$product->product_price}}" required autofocus>
                    <br>
                <select name="category_id" id="category_id" class="form-select" required>
                    {{-- <option value="" disabled selected hidden>Product Categories</option> --}}
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                </select>
                <input id="quantity" placeholder="Product Quantity" type="number" class="form-control mt-3" name="quantity" value="{{$product->quantity}}" required autofocus>
                <br>
                <textarea class="form-control" name="description" id="" cols="30" rows="5" placeholder="Product Description" >{{$product->description}}</textarea><br>
                <button type="submit" class="btn btn-success w-100">Edit Product</button>
            </form>
            {{-- 'user_id',
        'product_name',
        'product_price',
        'category_id',
        'quantity',
        'description', --}}
        </div>

    </div>
@endsection

