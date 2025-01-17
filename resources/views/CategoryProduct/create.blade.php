@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Add New Category Product') }}</h1>
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

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <div class="card">
            <div class="card-body">
              <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                  <label for="category_name">{{ __('Category Name') }}</label>
                  <input type="text" class="form-control @error('category_name') is-invalid @enderror"
                    id="category_name" name="category_name" value="{{ old('category_name') }}" placeholder="Enter Category Product name" required>
                  @error('category_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                  <button type="submit" class="btn btn-primary">{{ __('Save Supplier') }}</button>
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
