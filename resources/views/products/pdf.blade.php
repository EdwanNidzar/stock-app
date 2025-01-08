<!DOCTYPE html>
<html>

<head>
  <title>Product Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table,
    th,
    td {
      border: 1px solid black;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .badge {
      display: inline-block;
      padding: 0.25em 0.4em;
      font-size: 75%;
      font-weight: 700;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 0.25rem;
    }

    .badge-danger {
      color: #fff;
      background-color: #dc3545;
    }

    .badge-success {
      color: #fff;
      background-color: #28a745;
    }

    .badge-warning {
      color: #212529;
      background-color: #ffc107;
    }

    .d-flex {
      display: flex;
    }

    .align-items-center {
      align-items: center;
    }

    .mr-2 {
      margin-right: 0.5rem;
    }
  </style>
</head>

<body>
  <h1>Product Report</h1>
  <table>
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Supplier</th>
        <th>Category</th>
        <th>Stock</th>
        <th>Threshold</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
        <tr>
          <td>{{ $product->product_name }}</td>
          <td>{{ $product->supplier->name_supplier }}</td>
          <td>{{ $product->category->category_name }}</td>
          <td>
            @if ($product->stock < $product->threshold)
              <span class="badge badge-danger d-flex align-items-center">
                {{ $product->stock }}
              </span>
            @elseif ($product->stock == $product->threshold)
              <span class="badge badge-warning d-flex align-items-center">
                {{ $product->stock }}
              </span>
            @else
              <span class="badge badge-success d-flex align-items-center">
                {{ $product->stock }}
              </span>
            @endif
          </td>
          <td>{{ $product->threshold }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
