<!DOCTYPE html>
<html>

<head>
  <title>Category Product Report</title>
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
  <h1>Category Product Report</h1>
  <table>
    <thead>
      <tr>
        <th>Name Category</th>
        <th>Product Count</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($categories as $category)
        <tr>
          <td>{{ $category->category_name }}</td>
          <td>{{ $category->products_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
