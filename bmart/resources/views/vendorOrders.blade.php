@extends('layouts.app2')
@section('title', 'Vendor Orders')
@section('content')

<div id="section-cont" class="row p-5 m-3">
    <h3 style="font-weight:600;text-align:center">All Orders</h3>
    <h6 style="text-align:center; margin-bottom:40px;">Here is an overview of all your customer orders.</h6>
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
@if($list == 0 || $orders->isEmpty())
    <div class="bg-warning text-black rounded-3 py-1"style="font-weight:600; margin:auto; text-align:center;">
        No order records can be displayed.
    </div>
@else
@foreach ($orders as $orderId => $orderItems)
    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 mb-2">
        <div style="border: 1px solid rgba(0,0,0,0.2);" class="p-3 rounded-3 h-100">
        <form action="{{ route('orders.confirm') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $orderId }}">
            @foreach ($orderItems as $item)
                <input type="hidden" name="items[{{ $item->id }}][product_name]" value="{{ $item->product_name }}">
                <input type="hidden" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}">
                <input type="hidden" name="items[{{ $item->id }}][final_price]" value="{{ $item->finalPrice }}">
            @endforeach
        <div class="d-flex justify-content-between">
            <h5 style="font-weight:600">Order #{{ $orderId }}</h5>
            <span>
                @if ($orderItems[0]->status == 'confirmed')
                    <div class="bg-success text-white rounded-pill px-2" style="font-weight:600; font-size:10pt;">
                        {{ $orderItems[0]->status }}
                    </div>
                @elseif ($orderItems[0]->status == 'delivered')
                    <div class="bg-primary text-white rounded-pill px-2" style="font-weight:600; font-size:10pt">
                        {{ $orderItems[0]->status }}
                    </div>
                @elseif ($orderItems[0]->status == 'shipped')
                    <div class="text-white rounded-pill px-2" style="background-color: rgb(255, 115, 0);font-weight:600; font-size:10pt">
                        {{ $orderItems[0]->status }}
                    </div>
                @elseif ($orderItems[0]->status == 'cancelled')
                    <div class="text-white bg-danger rounded-pill px-2" style="font-weight:600; font-size:10pt">
                        {{ $orderItems[0]->status }}
                    </div>
                @elseif ($orderItems[0]->status == 'request rejected')
                    <div class="text-secondary border border-secondary rounded-pill px-2" style="font-weight:600; font-size:10pt">
                        rejected cancel request
                    </div>
                @else
                    <div class="bg-warning rounded-pill px-2" style="font-weight:600; font-size:10pt">
                        {{ $orderItems[0]->status }}
                    </div>
                @endif
            </span>
        </div>
        <p>
            <b>Customer:</b> {{ $orderItems[0]->user->name }} <br>
            <b>Address:</b> {{$orderItems[0]->address}} <br>
            <b>Total Price:</b> P{{ number_format($orderItems->totalPrice, 2) }}
        </p>
        <ul>
            @foreach ($orderItems as $item)
                <li>{{ $item->quantity }} x {{ $item->product_name }} - P{{ number_format($item->finalPrice, 2) }}</li>
            @endforeach
        </ul>

            @if($orderItems[0]->status == 'pending')
            <input type="hidden" name="action" value="">
            {{-- data-bs-toggle="modal" data-bs-target="#confirmModal" --}}
                <button type="submit" style="font-weight:600" class="btn btn-success w-100" >
                    <i class="fa-solid fa-check"></i>&nbspConfirm Order
                </button>
            @endif

            @if($orderItems[0]->status == 'confirmed'||$orderItems[0]->status == 'request rejected')
                <input type="hidden" name="action" value="markShipped">
                <button class="btn btn-primary w-100" type="submit" style="font-weight:600">Mark as Shipping</button>
            @endif

            @if($orderItems[0]->status == 'for approval')
                <p><b>Reason for cancellation:</b> {{$orderItems[0]->cancel_req}}</p>
                {{-- <input type="hidden" name="action" value="markCancelled"> --}}
                <button class="btn btn-danger w-100 mb-2" type="submit" name="action" value="markCancelled" style="font-weight:600">Confirm Cancel Request</button>
                {{-- <input type="hidden" name="action" value="markRejected"> --}}
                <button class="btn btn-secondary w-100" type="submit" name="action" value="markRejected" style="font-weight:600">Reject Request</button>
            @endif

            {{-- @if($orderItems[0]->status == 'cancelled'||$orderItems[0]->status == 'delivered')
                <form action="{{route('orders.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{$orderId}}"/>
                    <button class="btn btn-transparent border border-secondary text-secondary w-100" style="font-weight:600" type="submit" name="action" value="removeList">Remove from list</button>
                </form>
            @endif --}}

            @if($orderItems[0]->status == 'shipped')
                <input type="hidden" name="action" value="markDelivered">
                <button class="btn btn-transparent text-white w-100" style="background-color: rgb(255, 115, 0); font-weight:600" type="submit">Mark as Delivered</button>
            @endif
            </form>
        </div>
    </div>
    {{-- @endif --}}
@endforeach
@endif
</div>
@endsection
