@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Supplier Details') }}</h1>
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
              <h4>{{ $supplier->name_supplier }}</h4>
              <p><strong>{{ __('Supplier Address') }}:</strong> {{ $supplier->address_supplier }}</p>
              <p><strong>{{ __('Merchant') }}:</strong> {{ $supplier->merchant }}</p>
              <p><strong>{{ __('Social Media') }}:</strong> {{ $supplier->sosmed ?? '-' }}</p>
              <p><strong>{{ __('Phone') }}:</strong> {{ $supplier->phone ?? '-' }}</p>
              <p><strong>{{ __('WhatsApp') }}:</strong> {{ $supplier->whatsapp ?? '-' }}</p>
              <a href="{{ route('suppliers.edit', $supplier->id) }}"
                class="btn btn-primary">{{ __('Edit Supplier') }}</a>
              <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
            </div>
          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
@endsection
