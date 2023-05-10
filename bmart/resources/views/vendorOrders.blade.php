@extends('layouts.app2')
@section('title', 'Vendor Orders')
@section('content')
<style>
    .card {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    li{
        list-style: none;
    }
    </style>
    
    <div class="container-fluid p-5 login-wrap">
      <div class="row justify-content-center">
        @foreach ($orders as $order)
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="card p-4 h-100">
              <div>
                <h4 style="font-weight:600">
                  Order #{{ $order->id }} by {{ $order->name }} 
                  <span class="bg bg-warning rounded-pill px-2">
                    {{ $order->status }}
                  </span>
                </h4>
              </div>
              <div class="d-flex" style="flex-wrap: wrap;">
                <div class="px-2"><b>Buyer Address:</b> {{ $order->address }} </div>
                <div class="px-2"><b>Buyer Phone:</b> {{ $order->phone }}</div>
              </div>
              <br>
              <h6 style="font-weight:600">Ordered Products:</h6>
              <ul>
                @foreach ($order->products as $product)
                  <li>{{ $product->product_name }} x {{ $product->pivot->quantity }} | P{{ $product->pivot->price }} each</li>
                @endforeach
              </ul>
              <h6 style="font-weight:600">Total Price: P{{$order->totalPrice}}</h6>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    
@endsection