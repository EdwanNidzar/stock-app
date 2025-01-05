@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Product Details') }}</h1>
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
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-bordered">
                    <tr>
                      <th>{{ __('Product Name') }}</th>
                      <td>{{ $product->product_name }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Supplier') }}</th>
                      <td>{{ $product->supplier->name_supplier }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Category') }}</th>
                      <td>{{ $product->category->category_name }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Price') }}</th>
                      <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Stock') }}</th>
                      <td>{{ $product->stock }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table class="table table-bordered">
                    <tr>
                      <th>{{ __('Threshold') }}</th>
                      <td>{{ $product->threshold }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Description') }}</th>
                      <td>{{ $product->description }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('Product Photo') }}</th>
                      <td>
                        @if ($product->product_photo)
                          <img src="{{ asset('storage/' . $product->product_photo) }}" alt="{{ $product->product_name }}"
                            class="img-thumbnail" style="width: 150px; height: auto;">
                        @else
                          {{ __('No photo available') }}
                        @endif
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="mt-3">
                <a href="{{ route('products.edit', $product->id) }}"
                  class="btn btn-primary">{{ __('Edit Product') }}</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
@endsection
