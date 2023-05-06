@extends('layouts.app')
@section('title', 'Cart')
@section('content')

<div id="section-cont" class="p-5">
<h3 style="font-weight:600; text-align:center;">Hello {{Auth()->user()->name}}!</h3>
<h6 style="text-align:center;">Here is an overview of the products added to your cart!</h6>
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
<br>
    <table class="table sticky">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Qty</th>
                <th scope="col">Total</th>
                <th scope="col">Vendor Name</th>
                {{-- <th scope="col">Status</th> --}}
                <th scope="col"></th>
                {{-- <th scope="col" class="actions">
                  <a href="{{route('products.create')}}" class="text-white btn btn-primary rounded-3">
                    Add Product
                  </a>
                </th> --}}
            </tr>
        </thead>
        <tbody>
          @foreach($carts as $cart)
          <tr>
              <td><img src="{{asset('product_image/'.$cart->product_image)}}" alt="No product image" class="rounded-3" style="max-height:40px;"></td>
              <td>{{$cart->product_name}}</td>
              <td>{{$cart->category_name}}</td>
              <td>{{$cart->product_price}}</td>
              <td>
                  x{{$cart->cart_quantity}}
                  {{-- <input style="width:50px;" type="number" class="m-0 form-control quantity-input" value="{{$cart->cart_quantity}}" name="quantity" data-cart-id="{{$cart->cart_id}}"/> --}}
              </td>
              <td data-item-price="{{$cart->cart_quantity*$cart->product_price}}">{{$cart->cart_quantity*$cart->product_price}}</td>
              <td>{{$cart->vendor_name}}</td>
              <td class="actions">
                  <form method="POST" action="{{route('cart.destroy', $cart->cart_id) }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-transparent">
                          <i class="fa-solid fa-xmark" style="font-size:15pt;"></i>
                      </button>
                  </form>
              </td>
          </tr>
          @endforeach
          <tr>
              <th colspan="5">
                  <button class="btn btn-secondary">Clear Cart</button>
                  <button class="btn btn-success mx-2">Checkout</button>
              </th>
              <th colspan="3">
                  <h5 style="font-weight:600" class="d-flex">Total:
                      <span class="text-success"><h5 style="font-weight:600" id="total-price">&nbspP{{$totalPrice}}</h5>
                      </span>
                  </h5>
              </th>
          </tr>
      </tbody>
      </table>
    {{-- {{$carts->links('pagination::bootstrap-5')}} --}}
    </div>
</div>

@endsection
