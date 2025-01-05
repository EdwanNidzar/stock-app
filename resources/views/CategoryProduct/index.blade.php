@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Category Product') }}</h1>
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
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <div class="card">
            <div class="card-body p-0">

              <div class="mb-3 text-right p-3 ">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">Create New Categorie Product</a>
                <a href="{{ route('categories.exportPDF') }}" class="btn btn-info" target="_blank">Export PDF</a>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Name Category</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categories as $category)
                    <tr>
                      <td>{{ $category->category_name }}</td>
                      <td>
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                          style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this category?');">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix">
              {{ $categories->links() }}
            </div>
          </div>

          <!-- Tombol untuk membuka modal -->
          <div class="float-right position-fixed" style="bottom: 100px; right: 20px;">
            <button id="viewHistoryBtn" data-table="category_products" class="btn btn-info"><i class="fas fa-history"></i> View
              History</button>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <!-- Include the dynamic history modal -->
  @include('layouts/history') 


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
