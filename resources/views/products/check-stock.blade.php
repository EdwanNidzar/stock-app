@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Check Stock') }}</h1>
        </div>
      </div>
    </div>
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
            <div class="card-header">
              <h3 class="card-title">{{ __('Check Product Stock') }}</h3>
            </div>
            <div class="card-body">
              <!-- Form untuk input stok -->
              <form id="stockForm" action="{{ route('check-product-stock') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="products">{{ __('Select Products') }}</label>
                  <div id="product-list">
                    @foreach ($products as $product)
                      <div class="row mb-2 align-items-center">
                        <div class="col-md-2">
                          <img
                            src="{{ $product->product_photo ? asset('storage/' . $product->product_photo) : asset('images/default-product.png') }}"
                            alt="{{ $product->product_name }}" class="img-thumbnail" style="width: 100px;">
                        </div>
                        <div class="col-md-4">
                          <input type="hidden" name="products[{{ $loop->index }}][product_id]"
                            value="{{ $product->id }}">
                          <input type="text" class="form-control" value="{{ $product->product_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                          <input type="number" name="products[{{ $loop->index }}][real_stock]"
                            class="form-control real-stock" placeholder="{{ __('Enter real stock quantity') }}">
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Check Stock') }}</button>
                <button type="button" id="resetButton" class="btn btn-secondary">{{ __('Reset') }}</button>
              </form>
            </div>
          </div>

          <!-- Hasil laporan stok -->
          <div id="reportContainer">
            @if (isset($report))
              <div class="card mt-4">
                <div class="card-header">
                  <h3 class="card-title">{{ __('Stock Report') }}</h3>
                  <div class="float-right">
                    <!-- Tombol Simpan dengan Modal -->
                    <button type="button" id="saveReportButton" class="btn btn-success" data-toggle="modal"
                      data-target="#saveReportModal">
                      {{ __('Save Report') }}
                    </button>
                    <!-- Modal Konfirmasi -->
                    <div class="modal fade" id="saveReportModal" tabindex="-1" role="dialog"
                      aria-labelledby="saveReportModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="saveReportModalLabel">{{ __('Confirm Save') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            {{ __('Are you sure you want to save this report?') }}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                              {{ __('Cancel') }}
                            </button>
                            <form action="{{ route('store-stock') }}" method="POST" id="saveReportForm">
                              @csrf
                              @foreach ($report as $key => $item)
                                <input type="text" name="report[{{ $key }}][product_name]"
                                  value="{{ $item['product_name'] }}">
                                <input type="text" name="report[{{ $key }}][system_stock]"
                                  value="{{ $item['system_stock'] }}">
                                <input type="text" name="report[{{ $key }}][real_stock]"
                                  value="{{ $item['real_stock'] }}">
                                <input type="text" name="report[{{ $key }}][status]"
                                  value="{{ $item['status'] }}">
                              @endforeach
                              <input type="text" name="discipline_percentage" value="{{ $disciplinePercentage }}">
                              <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Tombol Export PDF -->
                    <form action="{{ route('export-stock-report') }}" method="POST" target="_blank"
                      style="display: inline;">
                      @csrf
                      <input type="hidden" name="report" value="{{ json_encode($report) }}">
                      <input type="hidden" name="disciplinePercentage" value="{{ $disciplinePercentage }}">
                      <button type="submit" class="btn btn-info">{{ __('Export PDF') }}</button>
                    </form>
                  </div>
                </div>
                <div class="card-body p-0">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('System Stock') }}</th>
                        <th>{{ __('Real Stock') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Stock Logs') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($report as $item)
                        <tr>
                          <td>
                            @if ($item['product_photo'])
                              <img src="{{ asset('storage/' . $item['product_photo']) }}"
                                alt="{{ $item['product_name'] }}" style="width: 50px;">
                            @else
                              {{ $item['product_name'] }}
                            @endif
                          </td>
                          <td>{{ $item['system_stock'] ?? '-' }}</td>
                          <td>{{ $item['real_stock'] }}</td>
                          <td>
                            <span
                              class="badge {{ $item['status'] === 'Stok Sesuai' ? 'badge-success' : 'badge-danger' }}">
                              {{ $item['status'] }}
                            </span>
                          </td>
                          <td>
                            @if (!empty($item['logs']))
                              <ul>
                                @foreach ($item['logs'] as $log)
                                  <li>
                                    <strong>{{ $log->type }}:</strong> {{ $log->quantity }}
                                    ({{ $log->created_at->format('d M Y') }})
                                    - {{ $log->user->name }}
                                  </li>
                                @endforeach
                              </ul>
                            @else
                              {{ __('No Logs Found') }}
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif

            @if (isset($disciplinePercentage) && $disciplinePercentage !== null)
              <div class="mt-4">
                <h4>{{ __('Displin Stok (Performansi Tim) :') }} {{ $disciplinePercentage }}%</h4>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmSaveReport() {
      return confirm("Apakah Anda yakin ingin menyimpan laporan stok ini?");
    }
  </script>

  <script>
    document.getElementById('resetButton').addEventListener('click', function() {

      document.querySelectorAll('.real-stock').forEach(input => input.value = '');

      // Reset stock report content
      const reportContainer = document.getElementById('reportContainer');
      if (reportContainer) {
        reportContainer.innerHTML = ''; // Clear report content
      }
    });
  </script>

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
