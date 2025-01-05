<!DOCTYPE html>
<html>

<head>
  <title>Suppliers Report</title>
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
  </style>
</head>

<body>
  <h1>Suppliers Report</h1>
  <table>
    <thead>
      <tr>
        <th>Name Supplier</th>
        <th>Address Supplier</th>
        <th>Merchant</th>
        <th>Product Count</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($suppliers as $supplier)
        <tr>
          <td>{{ $supplier->name_supplier }}</td>
          <td>{{ $supplier->address_supplier }}</td>
          <td>{{ $supplier->merchant }}</td>
          <td>{{ $supplier->products_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
