@extends('layouts.app')

@section('content')
<div id="section-cont" class="p-5">
    <h3 style="font-weight:600; text-align:center;">All Orders</h3>
    <h6 style="text-align:center;">Confirmed orders cannot be cancelled!</h6>
    <table class="table table-sticky table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                {{-- <th>City</th>
                <th>Country</th>
                <th>Postal Code</th>
                <th>Phone</th>
                <th>Email</th> --}}
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date:</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->address }}</td>
                    {{-- <td>{{ $order->city }}</td>
                    <td>{{ $order->country }}</td>
                    <td>{{ $order->postalcode }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->email }}</td> --}}
                    <td>{{ $order->total_price }}</td>
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
            @endforeach
        </tbody>
    </table>
    {{$orders->links('pagination::bootstrap-5')}}
</div>
    
@endsection
