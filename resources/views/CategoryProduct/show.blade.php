@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Category Product Details') }}</h1>
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
              <p><strong>{{ __('Category Product Name') }}:</strong> {{ $category->category_name }}</p>
              <a href="{{ route('categories.edit', $category->id) }}"
                class="btn btn-primary">{{ __('Edit Category Product') }}</a>
              <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
            </div>
          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
@endsection
