@extends('layouts.app2')
@section('title', 'Add New Product')
@section('content')
    <div class="d-flex container-fluid p-5 justify-content-center login-wrap">
        <div class="bg-light rounded-0 shadow-sm p-4 testform" style="border:2px solid #dedede;">
            <h3>Add a New Product</h3><hr>
            
            <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="bg-danger alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <ul>
                            <li>{{ $error }}</li>
                    </ul>
                </div>
                @endforeach
                @endif
                <input id="user_id" class="form-control" type="text" value="{{Auth::id()}}" name="user_id" readonly>
                <input id="product_name" placeholder="Product Name" type="text" class="form-control mt-3" name="product_name" value="{{ old('product_name') }}" autofocus>
                <input id="product_price" placeholder="Product Price" type="number" class="form-control mt-3" step="0.01" name="product_price" value="{{ old('product_price') }}" autofocus>
                    <br>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="" disabled selected hidden>Product Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                </select>
                <input id="quantity" placeholder="Product Quantity" type="number" class="form-control mt-3" name="quantity" value="{{ old('quantity') }}" autofocus>
                <br>
                <textarea class="form-control" name="description" id="" cols="30" rows="5" placeholder="Product Description"></textarea><br>
                <div>
                    <input type="file" name="product_image" class="form-control" onchange="previewFile(this)">
                    <img id="previewImg" alt="" style="max-width:100%; max-height: 200px; margin-top:20px; border-radius: 15px;"/>
                </div>
                <br>
                <button type="submit" class="btn btn-primary w-100">Add Product</button>
            </form>
            {{-- 'user_id',
        'product_name',
        'product_price',
        'category_id',
        'quantity',
        'description', --}}
        </div>

    </div>
@endsection

<script>
    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                $('#previewImg').attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }
</script>
