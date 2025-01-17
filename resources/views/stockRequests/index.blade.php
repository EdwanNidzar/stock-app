@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Stock Requests') }}</h1>
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

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Picture</th>
                    <th>Product</th>
                    <th>Request Date</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($stockRequests as $request)
                    <tr>

                      <td>
                        @if ($request->photo)
                          <img src="{{ asset('storage/' . $request->photo) }}" alt="{{ $request->product->product_name }}"
                            width="50" height="50">
                        @else
                          <span>No Image</span>
                        @endif
                      </td>
                      <td>{{ $request->product->product_name }}</td>
                      <td>{{ $request->created_at->format('Y-m-d') }}</td>
                      <td>{{ $request->user->name }}</td>
                      <td>{{ ucfirst($request->type) }}</td>
                      <td>{{ $request->quantity }}</td>
                      <td>{{ $request->note }}</td>
                      <td>{{ ucfirst($request->status) }}</td>
                      <td>
                        @if ($request->status == 'pending')
                          <!-- Cek jika status adalah pending -->
                          <form action="{{ route('stockRequests.approve', $request->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button class="btn btn-primary">Approve</button>
                          </form>
                          <form action="{{ route('stockRequests.reject', $request->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button class="btn btn-danger">Reject</button>
                          </form>
                        @else
                          <span>-</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

          </div>

        </div>
      </div>

      <!-- /.row -->
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
