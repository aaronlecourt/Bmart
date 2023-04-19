@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
<div class="container p-3">
    {{-- <div class="row justify-content-center"> --}}
        <div class="row row-cols-1 row-cols-md-5 g-4">
            @foreach($prods as $prod)
            <div class="col">
                <div class="card product-card" >
                    <img src="{{asset('product_image/'.$prod->product_image)}}" class="card-img-top" alt="Product Image" style="height: 200px;width: 100%;object-fit: cover; border-bottom:3px solid orange;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">{{$prod->product_name}}</h5>
                            <h5 class="card-text"><b>P{{$prod->product_price}}</b></h5>
                        </div>
                        
                        <p class="card-text">{{$prod->description}}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="card-text">{{$prod->category_name}} | {{$prod->name}}</small>
                            {{-- <input type="number" class="form-input" value="0"> --}}
                            <span class="input-group-text">Quantity:{{$prod->quantity}}</span>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button class="btn btn-warning" type="button">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    {{-- </div> --}}
</div>
@endsection