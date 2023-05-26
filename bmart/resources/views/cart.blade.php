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
                {{-- <th scope="col"></th> --}}
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
                <form method="POST" action="{{route('cart.update', $cart->cart_id) }}">
                    @csrf
                    @method('PUT')
                <span class="cart-quantity">x{{$cart->cart_quantity}}</span>
                <div class="input-group" style="white-space:nowrap; width:auto; display: none;">
                    <input type="number" class="form-control" style="" value="{{$cart->cart_quantity}}" name="quantity" data-cart-id="{{$cart->cart_id}}">
                    <div class="input-group-append" style="display: inline-flex;">
                        <button type="submit" class="btn btn-transparent" style="font-size: 0.7rem;">
                            <i class="fa-solid fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-transparent cancel-cart" style="font-size: 0.7rem; display: none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                
                <a href="#" class="edit-cart" style="font-size: 0.7rem;">
                    <i class="fa-solid fa-pen-nib"></i>
                </a>
                </form>
            </td>                     
              <td data-item-price="{{$cart->cart_quantity*$cart->product_price}}">{{$cart->cart_quantity*$cart->product_price}}</td>
              <td>({{$cart->vendor_id}}) {{$cart->vendor_name}}</td>
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
              <th colspan="3"> 
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Clear Cart</button>
                </form> 
                               
                  
              </th>
              <th colspan="5">
                  <h5 style="font-weight:600" class="d-flex">Total:
                      <span class="text-success"><h5 style="font-weight:600" id="total-price">&nbspP{{ number_format($totalPrice, 2, '.', ',') }}</h5>
                      </span>
                  </h5>
              </th>
          </tr>
          <tr>
            <th colspan="8">
                {{-- <button type="submit" class="btn btn-success w-100">Checkout</button> --}}
                <!-- Button trigger modal -->
                @if(Auth::check())
                    @php
                        $cartCount = DB::table('carts')->where('cart_userid', Auth::id())->count();
                        // dd($cartCount);
                    @endphp
                    @if($cartCount > 0)
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            Checkout
                        </button>
                    @else
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal" disabled>
                            Cart is empty.
                        </button>
                    @endif
                @endif
                
                <!-- Modal -->
                <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg bg-success text-white">
                        <h5 class="modal-title" style="font-weight:600" id="exampleModalLabel">Checkout</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                                @csrf
                                <fieldset class="form-group border p-3 col">
                                    <input type="hidden" name="user_id" value="{{Auth::id();}}">
                                    <h5 style="font-weight:600">Shipment Details</h5>
                                    <div class="form-group">
                                        <label for="username">Name:</label>
                                        <input type="text" class="form-control name required" id="name" name="name" value="{{$user->name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Address:</label>
                                        <input type="text" class="form-control address required" id="address" name="address" value="{{$user->address}}">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label for="email">City:</label>
                                            <input type="text" class="form-control city required" id="city" name="city" value="{{$user->city}}">
                                        </div>
                                        <div class="col">
                                            <label for="email">Country:</label>
                                            <input type="text" class="form-control country required" id="country" name="country" value="{{$user->country}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label for="email">Postal Code:</label>
                                            <input type="text" class="form-control postalcode required" id="postalcode" name="postalcode" value="{{$user->postalcode}}">
                                        </div>
                                        <div class="col">
                                            <label for="email">Phone Number:</label>
                                            <input type="text" class="form-control phone required" id="phone" name="phone" value="{{$user->number}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Email Address:</label>
                                        <input type="text" class="form-control email required" id="email" name="email" value="{{$user->email}}">
                                    </div>
                                </fieldset>
                                <br>
                                <fieldset class="form-group border p-3 col">
                                    <h5 style="font-weight:600">Order Summary</h5>
                                    @foreach($carts as $cart)
                                    
                                        <li>P{{$cart->cart_price*$cart->cart_quantity}}-{{$cart->product_name}} x{{$cart->cart_quantity}} sold by: Vendor #{{$cart->vendor_id}}-{{$cart->vendor_name}}</li>
                                    @endforeach
                                    <br>
                                    <input type="hidden" name="totalprice" value="{{$totalPrice}}">
                                    <h6 style="font-weight:600;" class="text-success">Total Cost:&nbspP{{ number_format($totalPrice, 2, '.', ',') }}</h6>
                                </fieldset>
                                <div class="modal-footer">
                                    @foreach($carts as $cart)
                                    <input type="hidden" name="cart_userid" value="{{$user->id}}">
                                    <input type="hidden" name="cart_categoryid" value="{{$cart->category_id}}">

                                        <input type="hidden" name="product_ids[]" value="{{$cart->product_id}}">
                                        <input type="hidden" name="vendor_ids[]" value="{{$cart->vendor_id}}">
                                        <input type="hidden" name="quantities[]" value="{{$cart->cart_quantity}}">
                                        <input type="hidden" name="prices[]" value="{{$cart->product_price}}">
                                    @endforeach
                                    <button type="submit" id="placeOrderBtn" class="btn btn-success w-100">Place Order</button>
                                </div>
                            </form>                          
                        </div>
                    </div>
                    </div>
                </div>
            </th>
          </tr>
      </tbody>
      </table>
    {{-- {{$carts->links('pagination::bootstrap-5')}} --}}
    </div>
</div>
<script>
$(document).ready(function() {
    $(".edit-cart").click(function(event) {
        event.preventDefault();
        $(this).hide();
        $(this).closest('td').find('.cart-quantity').hide();
        $(this).closest('td').find('.input-group').show().find(".btn-transparent").show();
    });

    $(".cancel-cart").click(function(event) {
        event.preventDefault();
        $(this).parents(".input-group").hide();
        $(this).parents(".input-group").siblings(".cart-quantity").show();
        $(this).parents(".input-group").siblings(".edit-cart").show();
    });
    
});


</script>   
@endsection
