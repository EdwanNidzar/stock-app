@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Product') }}</h1>
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
            <div class="card-body p-0">
              <div class="mb-3 text-right p-3">
                <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
                <a href="{{ route('products.exportPDF') }}" class="btn btn-info" target="_blank">Export PDF</a>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Supplier</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Threshold</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($products as $product)
                    <tr>
                      <td>{{ $product->product_name }}</td>
                      <td>{{ $product->supplier->name_supplier }}</td>
                      <td>{{ $product->category->category_name }}</td>
                      <td>
                        @if ($product->stock <= $product->threshold)
                          <span class="badge badge-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $product->stock }}
                          </span>
                        @else
                          <span class="badge badge-success d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $product->stock }}
                          </span>
                        @endif

                      </td>
                      <td>{{ $product->threshold }}</td>
                      <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                          data-target="#updateStockModal-{{ $product->id }}">
                          <i class="fas fa-box"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                          data-target="#productLogsModal-{{ $product->id }}">
                          <i class="fas fa-history"></i>
                        </button>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
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

                    <!-- Update Stock Modal -->
                    <div class="modal fade" id="updateStockModal-{{ $product->id }}" tabindex="-1"
                      aria-labelledby="updateStockModalLabel-{{ $product->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <form action="{{ route('products.updateStock', $product->id) }}" method="POST"
                          enctype="multipart/form-data">
                          @csrf
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="updateStockModalLabel-{{ $product->id }}">Update Stock for
                                {{ $product->product_name }}</h5>
                              <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="type-{{ $product->id }}" class="form-label">Type</label>
                                <select name="type" id="type-{{ $product->id }}" class="form-control" required>
                                  <option value="" disabled selected>Choose Type</option>
                                  <option value="in">In</option>
                                  <option value="out">Out</option>
                                  <option value="offer">Offer</option>
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="quantity-{{ $product->id }}" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity-{{ $product->id }}"
                                  class="form-control" required>
                              </div>
                              <div class="mb-3">
                                <label for="photo-{{ $product->id }}" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo-{{ $product->id }}" class="form-control"
                                  accept="image/*" required>
                              </div>
                              <div class="mb-3">
                                <label for="expired_at-{{ $product->id }}" class="form-label">Expired At</label>
                                <input type="date" name="expired_at" id="expired_at-{{ $product->id }}"
                                  class="form-control" required>
                              </div>
                              <div class="mb-3">
                                <label for="note-{{ $product->id }}" class="form-label">Note</label>
                                <textarea name="note" id="note-{{ $product->id }}" class="form-control"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Update Stock</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- End Update Stock Modal -->

                    <!-- Modal Product Logs -->
                    <div class="modal fade" id="productLogsModal-{{ $product->id }}" tabindex="-1"
                      aria-labelledby="productLogsModalLabel-{{ $product->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="productLogsModalLabel-{{ $product->id }}">Logs for
                              {{ $product->product_name }}</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            @forelse ($product->stockLogs as $log)
                              <div class="card mb-3">
                                <div class="card-header">
                                  <strong>{{ $log->user->name }}</strong> :
                                  <strong>{{ ucfirst($log->type) }}</strong> -
                                  {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}
                                </div>
                                <div class="card-body">
                                  <p><strong>Quantity:</strong> {{ $log->quantity }}</p>
                                  <p><strong>Note:</strong> {{ $log->note }}</p>
                                  <p>
                                    <strong>Expired:</strong>{{ \Carbon\Carbon::parse($log->expired_at)->format('d/m/Y') }}
                                  </p>
                                  @if ($log->photo)
                                    <p><strong>Photo:</strong> <a href="{{ asset('storage/' . $log->photo) }}"
                                        target="_blank">View Image</a></p>
                                  @else
                                    <p>No photo available</p>
                                  @endif
                                </div>
                              </div>
                            @empty
                              <p class="text-center">No logs available for this product.</p>
                            @endforelse
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Product Logs Modal -->
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix">
              {{ $products->links() }}
            </div>
          </div>

        </div>
      </div>

      <!-- Tombol untuk membuka modal -->
      <div class="float-right position-fixed" style="bottom: 100px; right: 20px;">
        <button id="viewHistoryBtn" data-table="products" class="btn btn-info"><i class="fas fa-history"></i> View History</button>
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
