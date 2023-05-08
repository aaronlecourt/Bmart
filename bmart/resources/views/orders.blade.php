@extends('layouts.app')
@section('title', 'Orders')
@section('content')
    <div class="d-flex container-fluid p-5 justify-content-center login-wrap">
        <h1>Order Details</h1>

<table>
  <tr>
    <td>Order ID:</td>
    <td>{{ $order->id }}</td>
  </tr>
  <tr>
    <td>Name:</td>
    <td>{{ $order->name }}</td>
  </tr>
  <tr>
    <td>Address:</td>
    <td>{{ $order->address }}</td>
  </tr>
  <tr>
    <td>City:</td>
    <td>{{ $order->city }}</td>
  </tr>
  <tr>
    <td>Country:</td>
    <td>{{ $order->country }}</td>
  </tr>
  <tr>
    <td>Postal Code:</td>
    <td>{{ $order->postalcode }}</td>
  </tr>
  <tr>
    <td>Phone:</td>
    <td>{{ $order->phone }}</td>
  </tr>
  <tr>
    <td>Email:</td>
    <td>{{ $order->email }}</td>
  </tr>
  <tr>
    <td>Total Cost:</td>
    <td>{{ $order->total_price }}</td>
  </tr>
  <tr>
    <td>Status:</td>
    <td>{{ $order->status }}</td>
  </tr>
</table>

<br>

<h2>Order Items</h2>

<table>
  <thead>
    <tr>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($order->items as $item)
      <tr>
        <td>{{ $item->product_name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ $item->price }}</td>
        <td>{{ $item->subtotal }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

    </div>
@endsection