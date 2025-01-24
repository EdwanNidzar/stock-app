@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Dashboard') }}</h1>
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
              <h2 class="card-title"><strong>{{ __('Visi') }}</strong></h2>
              <p class="card-text">
                Menjadi merek ayam tepung lokal yang terdepan dengan cita rasa lezat, harga terjangkau, serta berperan
                dalam mendukung kemajuan UMKM di Kalimantan Selatan.
              </p>
              <hr>
              <h2 class="card-title"><strong>{{ __('Misi') }}</strong></h2>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">1. Menyajikan ayam tepung berkualitas dengan harga yang bersahabat.</li>
                <li class="list-group-item">2. Menjaga kelezatan rasa yang konsisten dan sesuai dengan selera masyarakat.
                </li>
                <li class="list-group-item">3. Mendukung pertumbuhan UMKM melalui kemitraan dan pemberdayaan lokal.</li>
                <li class="list-group-item">4. Memberikan pelayanan yang ramah dan cepat demi kepuasan pelanggan.</li>
                <li class="list-group-item">5. Terus berinovasi dalam produk dan operasional untuk meningkatkan daya
                  saing.</li>
              </ul>
              <hr>
              <p>
                <strong>Alamat:</strong> Jl. H. Mistar Cokrokusumo, Loktabat Sel., Kec. Banjarbaru Selatan, Kota Banjar
                Baru, Kalimantan
                Selatan<br>
                <strong>Telepon:</strong> +6289513515040
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <p class="card-text">
                {{ __('Performa Stok Bulanan') }}
                <canvas id="performanceChart" width="400" height="200"></canvas>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body p-4">
              <form action="{{ route('home.usage') }}" method="GET" class="form-row" id="usage-form">
                <div class="form-group col-md-4">
                  <label for="product_id" class="form-label">Produk</label>
                  <select id="product_id" name="product_id" class="form-control @error('product_id') is-invalid @enderror"
                    required>
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                      <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                  </select>
                  @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-4">
                  <label for="start_date" class="form-label">Tanggal Mulai</label>
                  <input type="date" id="start_date" name="start_date"
                    class="form-control @error('start_date') is-invalid @enderror" required>
                  @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group col-md-4">
                  <label for="end_date" class="form-label">Tanggal Selesai</label>
                  <input type="date" id="end_date" name="end_date"
                    class="form-control @error('end_date') is-invalid @enderror" required>
                  @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </form>
              <a href="{{ route('home') }}"class="btn btn-secondary mt-3">Reset</a>
              <button type="submit" class="btn btn-primary mt-3" form="usage-form">Lihat Penggunaan</button>
            </div>
          </div>
        </div>
      </div>

      {{-- <!-- Chart for Performa Stok Bulanan--> use bar chart --}}
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Data dari Controller
          const labels = @json($monthlyPerformance->pluck('month'));
          const data = @json($monthlyPerformance->pluck('average_discipline'));

          // Render Bar Chart
          const ctx = document.getElementById('performanceChart').getContext('2d');
          new Chart(ctx, {
            type: 'bar', // Jenis chart bar
            data: {
              labels: labels, // Bulan
              datasets: [{
                label: 'Rata-rata Performa (%)',
                data: data, // Data performa
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Warna bar
                borderColor: 'rgba(54, 162, 235, 1)', // Warna border
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  position: 'top',
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return `${context.raw}%`; // Format tooltip
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Rata-rata Performa (%)'
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: 'Bulan'
                  }
                }
              }
            }
          });
        });
      </script>
      <!-- END Chart for Performa Stok Bulanan-->

      {{-- <!-- Chart for Performa Stok Bulanan--> use line chart --}}
      {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Data dari Controller
          const labels = @json($monthlyPerformance->pluck('month'));
          const data = @json($monthlyPerformance->pluck('average_discipline'));

          // Render Chart
          const ctx = document.getElementById('performanceChart').getContext('2d');
          new Chart(ctx, {
            type: 'line', // Jenis chart
            data: {
              labels: labels, // Bulan
              datasets: [{
                label: 'Rata-rata Performa (%)',
                data: data, // Data performa
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                pointHoverRadius: 8, // Larger point on hover
                pointHoverBackgroundColor: 'rgba(255, 99, 132, 1)',
                pointHoverBorderColor: 'rgba(255, 99, 132, 1)',
              }]
            },
            options: {
              responsive: true,
              interaction: {
                mode: 'index',
                intersect: false, // Show data from multiple points on hover
              },
              plugins: {
                legend: {
                  position: 'top',
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return `Performa: ${context.raw}%`; // Format tooltip
                    }
                  }
                },
                zoom: {
                  pan: {
                    enabled: true, // Enable panning
                    mode: 'x', // Pan horizontally
                  },
                  zoom: {
                    wheel: {
                      enabled: true, // Zoom with mouse wheel
                    },
                    pinch: {
                      enabled: true, // Zoom with touch gestures
                    },
                    mode: 'x', // Zoom horizontally
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Rata-rata Performa (%)'
                  },
                  ticks: {
                    callback: function(value) {
                      return `${value}%`; // Add '%' to y-axis labels
                    }
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: 'Bulan'
                  }
                }
              }
            }
          });
        });
      </script> --}}
      <!-- END Chart for Performa Stok Bulanan-->

      <!-- Chart -->
      @if (isset($labels) && isset($datasets))
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <canvas id="productUsageChart" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          var ctx = document.getElementById('productUsageChart').getContext('2d');
          var productUsageChart = new Chart(ctx, {
            type: 'bar', // Bar chart
            data: {
              labels: {!! json_encode($labels) !!}, // Dates for X-axis
              datasets: {!! json_encode($datasets) !!} // Stock out data for each product
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true // Start Y-axis from 0
                }
              }
            }
          });
        </script>
      @endif
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
@endsection
