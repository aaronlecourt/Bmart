@extends('layouts.app')
@section('title', 'All Orders')
@section('content')
<div id="section-cont" class="p-5">
    @if($vendorOrders->isEmpty())
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Confirmed orders cannot be cancelled!</h6>

    <div class="bg-warning text-black rounded-3 py-1"style="font-weight:600; margin:auto; text-align:center;">
        You have not made any orders yet!
    </div>
    <div class="px-4">
    @else
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Confirmed orders cannot be cancelled!</h6>
    <div class="px-4">
    @foreach($vendorOrders as $vendorOrder)
        @foreach($vendorOrder->order_items->groupBy('order_id') as $orderItems)
            <div class="rounded-3 p-3 my-3" style="border:1px solid rgba(0,0,0,0.2)">
                <div class="d-flex justify-content-between" style="font-weight: 600;display:inline-flex; wrap:no-wrap;">
                    <h5 style="font-weight:600;" class="p-0">Order #{{ $orderItems->first()->order_id }} for {{ $vendorOrder->vendor_name }}</h5>
                    
                    <div><span class="text-secondary"><i class="fa-solid fa-truck-fast"></i> {{ $orderItems->first()->address }}</span></div>
                    <div><span>Total Price: P{{ $orderItems->sum('order_price') }}</span></div>
                    <div>
                    <span class="bg-warning px-3 py-1 rounded-pill mx-3">
                        {{ $orderItems->first()->status }}
                    </span>
                    </div>
                    <button type="button" style="font-weight:600" class="btn btn-transparent px-1 py-0 text-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order</button>
                </div>
                
                <span><i class="fa-solid fa-basket-shopping"></i> </span>
                @foreach($orderItems as $orderItem)
                
                    <span>{{ $orderItem->product_name }} x {{ $orderItem->quantity }},</span>
                        {{-- <li>Product Price: {{ $orderItem->product_price }}</li> --}}        
                @endforeach
        </div>
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                <h5 style="font-weight:600" class="modal-title text-white" id="cancelOrderModalLabel">Cancel Order Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.cancel', $orderItems->first()->order_id) }}" method="POST">
                    @csrf
                <div class="modal-body">
                <p>Cancelling this request requires <b>stating the reason for cancellation</b>. Vendor reviews your cancellation request and can either confirm or reject it.</p>
                    <textarea class="form-control"name="cancelReason" id="cancelReason" placeholder="Type in your reason for cancelling the order." required></textarea>
                </div>
                <div class="modal-footer">
                
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Request to Cancel</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        
        @endforeach
    @endforeach
    @endif
    </div>
</div>
    
@endsection
