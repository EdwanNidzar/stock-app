<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('Stock Report') }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    img {
      width: 50px;
      height: auto;
    }
  </style>
</head>

<body>
  <h1>{{ __('Stock Report') }} - {{ \Carbon\Carbon::now()->format('d M Y') }} </h1>

  <table>
    <thead>
      <tr>
        <th>{{ __('Product Name') }}</th>
        <th>{{ __('Product Photo') }}</th>
        <th>{{ __('System Stock') }}</th>
        <th>{{ __('Real Stock') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Stock Logs') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($modifiedReport as $item)
        <tr>
          <td>{{ $item['product_name'] }}</td>
          <td>
            <img src="{{ public_path('storage/' . $item['product_photo']) }}" alt="{{ $item['product_name'] }}">
          </td>
          <td>{{ $item['system_stock'] ?? '-' }}</td>
          <td>{{ $item['real_stock'] }}</td>
          <td>
            <span>{{ $item['status'] }}</span>
          </td>
          <td>
            @if (!empty($item['logs']))
              <ul>
                @foreach ($item['logs'] as $log)
                  <li>
                    <strong>{{ $log['type'] ?? '-' }}:</strong> {{ $log['quantity'] ?? '-' }}
                    ({{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y') }})
                    <!-- Fetch user name using user_id -->
                    - {{ $log['user_id'] ? \App\Models\User::find($log['user_id'])->name : __('Unknown User') }}
                  </li>
                @endforeach
              </ul>
            @else
              {{ __('No Logs Found') }}
            @endif
          </td>
        </tr>
      @endforeach
      @if (isset($disciplinePercentage))
        <div class="mt-4">
          <h4>{{ __('Displin Stok (Performansi Tim) :') }} {{ $disciplinePercentage }}%</h4>
        </div>
      @endif
    </tbody>
  </table>
</body>

</html>
