<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penggunaan Produk</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    h3,
    h5 {
      margin: 0;
    }

    .product-photo {
      width: 100px;
      height: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .table th {
      text-align: left;
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <h2>Laporan Penggunaan Produk</h2>
  <p>Tanggal: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
  <hr>

  @foreach ($usageData as $data)
    <div>
      <h3>{{ $data['product_name'] }}</h3>
      <p>Total Stok Keluar: {{ $data['total_stock_out'] }} {{ $data['unit'] }}</p>
      <p>Penggunaan per Hari: {{ number_format($data['usage_per_day'], 2) }} {{ $data['unit'] }}</p>
      <h5>Detail Pengeluaran Stok:</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Pengguna</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data['stock_logs'] as $log)
            <tr>
              <td>{{ $log->created_at->format('d M Y') }}</td>
              <td>{{ $log->quantity }} {{ $data['unit'] }}</td>
              <td>{{ $log->user ? $log->user->name : 'Admin' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <hr>
  @endforeach
</body>

</html>
