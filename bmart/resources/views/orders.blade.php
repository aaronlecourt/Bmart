@extends('layouts.app')
@section('title', 'All Orders')
@section('content')
<div id="section-cont" class="p-5">
    @if($list == 0 || $vendorOrders->isEmpty())
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Confirmed orders cannot be cancelled!</h6>

    <div class="bg-warning text-black rounded-3 py-1"style="font-weight:600; margin:auto; text-align:center;">
        No order records can be displayed.
    </div>
    <div class="px-4">
    @else
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Only pending orders can be cancelled.</h6>
    <div class="px-4">
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
        @foreach($vendorOrders as $vendorOrder)
        @foreach($vendorOrder->order_items->groupBy('order_id') as $orderId => $orderItems)
            @if($orderItems->first()->cancel_status == 1)
                {{-- hide delivered orders --}}
            @else
            <div class="rounded-3 p-3 my-3" style="border:1px solid rgba(0,0,0,0.2)">
                <div class="d-flex justify-content-between" style="font-weight: 600;display:inline-flex; wrap:no-wrap;">
                    <h5 style="font-weight:600;" class="p-0">Order #{{ $orderId }} for {{ $vendorOrder->vendor_name }}</h5>
                        
                    <div><span class="text-secondary"><i class="fa-solid fa-truck-fast"></i> {{ $orderItems->first()->address }}</span></div>
                    <div><span>Total Price: P{{ $orderItems->sum('order_price') }}</span></div>

                    <div>
                        <span>
                            @if ($orderItems->first()->status == 'confirmed')
                            <div class="bg-success text-white rounded-pill px-2" style="font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @elseif ($orderItems->first()->status == 'delivered')
                            <div class="bg-primary text-white rounded-pill px-2" style="font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @elseif ($orderItems->first()->status == 'shipped')
                            <div class="text-white rounded-pill px-2" style="background-color: rgb(255, 115, 0);font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @elseif ($orderItems->first()->status == 'cancelled')
                            <div class="bg-danger text-white rounded-pill px-2" style="font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @elseif ($orderItems->first()->status == 'request rejected')
                            <div class="border border-secondary text-secondary rounded-pill px-2" style="font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @elseif ($orderItems->first()->status == 'for approval')
                            <div class="text-white rounded-pill px-2" style="background-color: rgb(122, 122, 122);font-weight:600; font-size:10pt">
                                {{ $orderItems->first()->status }}
                            </div>
                            @else
                                <div class="bg-warning rounded-pill px-2" style="font-weight:600; font-size:10pt">
                                    {{ $orderItems->first()->status }}
                                </div>
                            @endif
                        </span>
                    </div>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                    @if($orderItems->first()->status == 'pending')
                        <button type="button" style="font-weight:600" class="btn btn-transparent px-1 py-0 text-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{$orderItems->first()->id}}">Cancel Order</button>
                    @elseif($orderItems->first()->status == 'for approval')
                        <input type="hidden" name="order_id" value="{{$orderId}}"/>
                        <input type="hidden" name="vendor_id" value="{{$orderItems->first()->vendor_id}}"/>
                        <button type="submit" style="font-weight:600" class="btn btn-transparent px-1 py-0 text-danger" name="action" value="cancelReq">Cancel Request</button>
                    @elseif($orderItems->first()->status == 'cancelled'||$orderItems->first()->status == 'delivered')
                        <input type="hidden" name="order_id" value="{{$orderId}}"/>
                        <input type="hidden" name="vendor_id" value="{{$orderItems->first()->vendor_id}}"/>
                        <button type="submit" style="font-weight:600" class="btn btn-transparent px-1 py-0" name="action" value="removeList">Remove from list</button>
                    @else
                        <button type="button" style="font-weight:600; border:none; opacity:0;" disabled>Cancel Order</button>
                    @endif
                </div>
            </form>        
                <span><i class="fa-solid fa-basket-shopping"></i> </span>
                @foreach($orderItems as $orderItem)
                    <span>{{ $orderItem->product_name }} x {{ $orderItem->quantity }},</span>      
                @endforeach
            </div>
            <div class="modal fade" id="cancelOrderModal{{$orderItems->first()->id}}" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                    <h5 style="font-weight:600" class="modal-title text-white" id="cancelOrderModalLabel">Cancel Order Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('orders.cancel') }}" method="POST">
                        @csrf
                    <div class="modal-body">
                    <p>Cancelling this request requires <b>stating the reason for cancellation</b>. Vendor reviews your cancellation request and can either confirm or reject it.</p>
                        <textarea class="form-control" name="cancelReason" id="cancelReason" placeholder="Type in your reason for cancelling the order." required></textarea>
                        <input type="hidden" name="cancel_status" id="cancel_status" value="0"/>
                        <input type="hidden" name="order_id" value="{{$orderId}}">

                        <input type="hidden" name="vendor_id" id="vendor_id" value="{{$orderItem->vendor_id}}">
                        @foreach ($orderItems as $orderItem)
                            <input type="hidden" name="product_ids[]" value="{{ $orderItem->product_id }}">
                        @endforeach
                        {{-- <input type="hidden" name="product_ids[]" id="product_ids[]" value="{{implode(',', $orderItems->pluck('product_id')->toArray())}}"> --}}
                        {{-- <p>{{$orderItem->id}}-{{$orderItem->vendor_id}}--{{ implode(',', $orderItems->pluck('product_id')->toArray())}}</p> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Request to Cancel</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            
            @endif
        @endforeach
    @endforeach
            
    </div>
    @endif
</div>
    
@endsection
