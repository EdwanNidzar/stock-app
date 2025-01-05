@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Add New Product') }}</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <form action="{{ route('products.store') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="product_name">{{ __('Product Name') }}</label>
                      <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                        id="product_name" name="product_name" value="{{ old('product_name') }}"
                        placeholder="Enter Product name" required>
                      @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="supplier_id">{{ __('Supplier') }}</label>
                      <select class="form-control @error('supplier_id') is-invalid @enderror" id="supplier_id"
                        name="supplier_id" required>
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                          <option value="{{ $supplier->id }}">{{ $supplier->name_supplier }}</option>
                        @endforeach
                      </select>
                      @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="category_id">{{ __('Category') }}</label>
                      <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                        name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                      </select>
                      @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="product_photo">{{ __('Product Photo') }}</label>
                      <input type="file" class="form-control @error('product_photo') is-invalid @enderror"
                        id="product_photo" name="product_photo">
                      @error('product_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="form-group">
                      <label for="stock">{{ __('Stock') }}</label>
                      <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                        name="stock" value="{{ old('stock', 0) }}" placeholder="Enter Stock. Default is 0">
                      @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="price">{{ __('Price') }}</label>
                      <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" value="{{ old('price') }}" placeholder="Enter Product Price" required>
                      @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="threshold">{{ __('Threshold') }}</label>
                      <input type="number" class="form-control @error('threshold') is-invalid @enderror" id="threshold"
                        name="threshold" value="{{ old('threshold') }}" placeholder="Enter Threshold" required>
                      @error('threshold')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="description">{{ __('Description') }}</label>
                      <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="4" placeholder="Enter Product Description">{{ old('description') }}</textarea>
                      @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                  <button type="submit" class="btn btn-primary">{{ __('Save Product') }}</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          alert.classList.remove('show');
          alert.classList.add('fade');
          setTimeout(function() {
            alert.remove();
          }, 150); // Wait for the fade transition to complete before removing
        });
      }, 3000);
    });
  </script>
@endsection
