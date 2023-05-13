@extends('layouts.app2')
@section('title', 'Categories')
@section('content')
<div id="section-cont" class="p-5">
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
    <h5 style="font-weight:600">Add Categories +</h5>
    <form action="{{ route('categories.store') }}" method="post">
        @csrf
        <div class="mb-3 d-flex">
                @if ($remainingCategories->isEmpty())
                    <select class="form-select me-2 text-secondary" name="category_id" id="category_id" disabled>
                        <option value="" selected >All categories are added.</option>
                    </select>            
                    <input type="submit" value="Add Category" class="btn btn-success" disabled>
                @else
                <select class="form-select me-2" name="category_id" id="category_id">
                    <option value="" hidden selected>Select a category</option>
                    @foreach ($remainingCategories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>            
                <input type="submit" value="Add Category" class="btn btn-success">
                @endif
        </div>
    </form>    
    <hr>
    <h5 style="font-weight:600">Your Categories</h5>
        @foreach($categories as $category)
        <div style="display: inline-flex; flex-wrap: wrap">
            <div class="px-4 py-1 m-1 rounded-pill" style="border:1px solid rgba(0,0,0,0.2);">
                <span style="font-weight:600">{{ $category->category_name }}</span>
                <button type="button" class="btn btn-transparent px-2 py-0 delete-category" data-category-id="{{ $category->id }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    <i class="fa-solid fa-xmark"></i>
                </button>                
            </div>
        </div>
        @endforeach
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="confirmDeleteModalLabel" style="font-weight:600"><span id="category-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                
                <div class="modal-body">
                  Are you sure you want to delete this category? All your products that are under this category will also be deleted.
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form> 
                </div>
              </div>
            </div>
        </div>
</div>
<script>
    $(document).ready(function() {
        $('.delete-category').click(function() {
            var categoryId = $(this).data('category-id');
            var categoryName = $(this).closest('.rounded-pill').find('span').text();
            $('#confirmDeleteModalLabel #category-name').html('<h5 style="font-weight:600">Delete ' + categoryName + '?</h5>');
            $('#confirmDeleteModal form').attr('action', '{{ route("categories.destroy", ":id") }}'.replace(':id', categoryId));
        });
    });
</script>
@endsection
