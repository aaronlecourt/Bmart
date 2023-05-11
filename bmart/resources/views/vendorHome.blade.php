@extends('layouts.app2')
@section('title', 'Vendor Home')
@section('content')

<div id="section-cont">
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
    <h3 style="font-weight:600;">Hello {{Auth()->user()->name}}!</h3>
    <h6>Here is an overview of your products!</h6>
    <br>
  <div class="container-fluid productTable">
      @if($products->isEmpty())
        <table class="table sticky">
            <thead>
              <tr>
                <td colspan="10" style="font-weight:600">No such product was found in your product records.</td>
              </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Description</th>
                    <th scope="col">Image</th>
                    <th scope="col">Created on:</th>
                    <th scope="col">Updated on:</th>
                    <th scope="col" class="actions">
                      <a href="{{route('products.create')}}" class="text-white btn btn-primary rounded-3">
                        Add Product
                      </a>
                    </th>
                </tr>
            </thead>
        </table>
        @else
        <table class="table sticky">
          <thead>
            <tr>
              <td colspan="10" style="font-weight:600">Showing {{$count}} result(s).</td>
            </tr>
              <tr>
                  <th scope="col">#</th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Category</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Description</th>
                  <th scope="col">Image</th>
                  <th scope="col">Created on:</th>
                  <th scope="col">Updated on:</th>
                  <th scope="col" class="actions">
                    <a href="{{route('products.create')}}" class="text-white btn btn-primary rounded-3">
                      Add Product
                    </a>
                  </th>
              </tr>
          </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{$product->prod_id}}</td>
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->product_price}}</td>
                    <td>{{$product->category_name}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->description}}</td>
                    <td><img src="{{asset('product_image/'.$product->product_image)}}" alt="No product image" class="rounded-3" style="max-height:40px;"></td>
                    <td>{{date('F d,Y', strtotime($product->created_at))}}</td>
                    <td>{{date('F d,Y', strtotime($product->updated_at))}}</td>
                    <td class="actions">
                      <a href="{{route('products.edit', $product->prod_id)}}" class="text-white btn btn-success rounded-3">
                        Edit</a>
                      <a href="#" class=" text-white btn btn-danger rounded-3" data-bs-toggle="modal" data-bs-target="#delete-modal">
                        Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Delete Modal -->
        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="delete-modal-label"><i class="fa-sharp fa-solid fa-trash-can"></i>&nbspDelete Confirmation</h5>
                <span class="closebtn" data-bs-dismiss="modal" aria-label="Close">&times;</span>
              </div>
              <div class="modal-body">
                <div class="row container-fluid p-3 rounded-3" style="border:1px solid rgba(0,0,0,0.2); margin:auto;">
                  <div class="col d-flex align-items-center justify-content-center">
                    <img src="{{asset('product_image/'.$product->product_image)}}" alt="No product image" class="rounded-3" style="max-height:80px;">
                  </div>
                  <div class="col">
                    <div class="row ">
                      <h4>{{$product->product_name}} ({{$product->category_name}})</h4>
                    </div>
                    <div class="row">
                      <p>{{$product->description}}
                        <br>Price: {{$product->product_price}} | Quantity: {{$product->quantity}}</p>
                    </div>
                  </div>
                </div>
               <br>
                <p>Are you sure you want to delete this product? Enter your password to confirm:</p>
                <form method="POST" action="{{ route('products.destroy', $product->prod_id) }}">
                  @csrf
                  @method('DELETE')
                  <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Type your current password" required>
                  </div><br>
                  <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
    <br>
          {{-- <div class="container-fluid p-0" style="padding:0; margin:0;"> --}}
            {{$products->links('pagination::bootstrap-5')}}
          {{-- </div> --}}
        @endif
</div>
</div>

@endsection