@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Product Usage Result') }}</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">

          <!-- Card -->
          <div class="card">
            <div class="card-body">

              <!-- Date Range -->
              <p class="mb-3">Tanggal: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>

              <!-- Display each product's usage data -->
              @foreach ($usageData as $data)
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <h3>{{ $data['product_name'] }}</h3>
                    <p>Total Stok Keluar: {{ $data['total_stock_out'] }} {{ $data['unit'] }}</p>
                    <p>Penggunaan per Hari: {{ number_format($data['usage_per_day'], 2) }} {{ $data['unit'] }}</p>
  
                    <!-- Display Stock Logs -->
                    <h5>Detail Pengeluaran Stok:</h5>
                    <ul>
                      @foreach ($data['stock_logs'] as $log)
                        <li>
                          <strong>{{ $log->created_at->format('d M Y') }}</strong> -
                          {{ $log->quantity }} {{ $data['unit'] }} keluar
                          @if ($log->user)
                            oleh {{ $log->user->name }} <!-- Assuming the user has a 'name' attribute -->
                          @else
                            oleh Admin
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <div class="col-6">
                  <img src="{{ Storage::url($data['product_photo']) }}" 
                  alt="{{ $data['product_name'] }}" 
                  class="img-thumbnail me-3"
                  style="max-width: 200px;">
                </div>
              </div>
                <hr>
              @endforeach

              <!-- Back Button -->
              <a href="{{ route('product.usage.form') }}" class="btn btn-primary mt-3">Kembali</a>
              <a href="{{ route('product.usage.pdf', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-success mt-3" target="_blank">Unduh PDF</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
