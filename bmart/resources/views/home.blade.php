@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
<div class="container-fluid p-5">
<div class="d-flex">
    <div class="p-2" style="width: 200px;">
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
        <form action="">
            <h6><b>Filter By Category:</b></h6>
            @foreach($categs as $cat)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="">
                <label class="form-check-label" for="">{{$cat->category_name}}</label>
              </div>
            @endforeach
            <br>
            <h6><b>Filter By Vendor:</b></h6>
            @foreach($vend as $ven)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="">
                <label class="form-check-label" for="">{{$ven->name}}</label>
              </div>
            @endforeach
        </form>
    </div>
    @if($prods->isEmpty())
    <div class="container-fluid rounded-3" style="border: 1px solid rgba(0,0,0,0.1);">
        <div class="row">
            <div class="col-sm-6 p-5">
            <p>No "{{$srch}}" product records were found.</p>
            </div>
        </div>
    </div>
    @else    
    <div class="container-fluid rounded-3" style="border: 1px solid rgba(0,0,0,0.1);">
        <div class="row">
            @foreach($prods as $prod)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 p-2">
                <form action="{{route('cart.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card" >
                        <img src="{{asset('product_image/'.$prod->product_image)}}" class="card-img-top" alt="Product Image" style="height: 200px;width: 100%;object-fit: cover; border-bottom:3px solid orange;">
                        <input type="hidden" value="{{$prod->product_image}}" name="product_image">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{$prod->product_name}}</h5>
                                    <input type="hidden" value="{{$userId}}" name="userid">
                                    <input type="hidden" value="{{$prod->prod_id}}" name="product_id">
                                <h5 class="card-text"><b>P{{$prod->product_price}}</b></h5>
                                    <input type="hidden" value="{{$prod->product_price}}" name="product_price">
                            </div>
                            
                            <p class="card-text">{{$prod->description}}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="card-text">{{$prod->category_name}} | {{$prod->name}}</small>
                                    <input type="hidden" value="{{$prod->categ_id}}" name="categ_id">
                                    <input type="hidden" value="{{$prod->name}}" name="vendor_name">
                                <input type="number" class="form-control w-50" value="0" name="quantity"/>
                                {{-- <span class="input-group-text">Quantity:{{$prod->quantity}}</span> --}}
                            </div>
                            <br>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-warning" type="submit">
                                        Add to Cart <i class="fa-sharp fa-solid fa-plus"></i>
                                    </button>
                                </div>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
        {{$prods->links('pagination::bootstrap-5')}}
    </div>
    @endif
</div>
</div>
@endsection