@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
<div class="container-fluid p-5">
<div class="d-flex">
    <div class="p-2" style="width: 200px;">
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
                <div class="card" >
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
                            <button class="btn btn-warning" type="button">
                                Add to Cart <i class="fa-sharp fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{$prods->links()}}
    </div>
    @endif
</div>
</div>
@endsection