@extends('layouts.app')
@section('title', 'All Orders')
@section('content')
<div id="section-cont" class="p-5">
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Confirmed orders cannot be cancelled!</h6>
    <table class="table table-sticky table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Shipping Address</th>
                {{-- <th>City</th>
                <th>Country</th>
                <th>Postal Code</th> --}}
                <th>Phone</th>
                <th>Email</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date:</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->address }}, {{ $order->city }}, {{ $order->postalcode }}, {{ $order->country }} </td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->email }}</td>
                    <td>P{{ $order->total_price }}</td>
                    <td>
                        <span class="bg bg-warning rounded-pill px-2">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('F d,Y') }}</td>
                    
                    <td>
                        <button type="submit" class="btn btn-transparent">
                            <i class="fa-solid fa-xmark" style="font-size:15pt;"></i>
                        </button>
                    </td>
                </tr>
                
                
                <!-- Modal Body -->
                {{-- <div class="modal fade" id="viewOrder" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" style="font-weight:600;" id="modalTitleId">View Order {{ $order->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <li></li>
                                <li>{{ $order->name }}</li>
                                <li>{{ $order->address }}</li>
                                <li>{{ $order->city }}</li>
                                <li>{{ $order->country }}</li>
                                <li>{{ $order->postalcode }}</li>
                                <li>{{ $order->phone }}</li>
                                <li>{{ $order->email }}</li>
                                <li>P{{ $order->total_price }}</li>
                                <li>
                                    <span class="bg bg-warning rounded-pill px-2">
                                        {{ $order->status }}
                                    </span>
                                </li>
                                <li>{{ $order->created_at->format('F d,Y') }}</li>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
                
                <!-- Optional: Place to the bottom of scripts -->
                <script>
                    const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)
                
                </script>
            @endforeach
        </tbody>
    </table>
    {{$orders->links('pagination::bootstrap-5')}}
</div>
    
@endsection
