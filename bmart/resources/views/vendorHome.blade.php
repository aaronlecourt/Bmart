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
        <table class="table sticky">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Description</th>
                    <th scope="col">Image</th>
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
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->product_price}}</td>
                    <td>{{$product->category_name}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->description}}</td>
                    <td><img src="{{asset('product_image/'.$product->product_image)}}" alt="No product image" class="rounded-3" style="max-height:40px;"></td>
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
    </div>
    <br>
    <div style="font-size:13px;">{{$products->links()}}</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="delete-modal-label"><i class="fa-sharp fa-solid fa-trash-can"></i>&nbspConfirm Delete</h5>
          <span class="closebtn" data-bs-dismiss="modal" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this product? Enter your password to confirm:</p>
          <form method="POST" action="{{ route('products.destroy', $product->prod_id) }}">
            @csrf
            @method('DELETE')
            <div class="form-group">
              <input type="password" name="password" class="form-control" required>
            </div><br>
            <button type="submit" class="btn btn-danger">Confirm Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
@endsection