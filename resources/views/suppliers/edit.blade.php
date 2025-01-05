@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Edit Supplier') }}</h1>
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
            <div class="card-body">
              <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                  <label for="name_supplier">{{ __('Supplier Name') }}</label>
                  <input type="text" class="form-control @error('name_supplier') is-invalid @enderror"
                    id="name_supplier" name="name_supplier" value="{{ old('name_supplier', $supplier->name_supplier) }}"
                    placeholder="Enter supplier name" required>
                  @error('name_supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="address_supplier">{{ __('Supplier Address') }}</label>
                  <textarea class="form-control @error('address_supplier') is-invalid @enderror" id="address_supplier"
                    name="address_supplier" rows="3" placeholder="Enter supplier address" required>{{ old('address_supplier', $supplier->address_supplier) }}</textarea>
                  @error('address_supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="merchant">{{ __('Merchant') }}</label>
                  <input type="text" class="form-control @error('merchant') is-invalid @enderror" id="merchant"
                    name="merchant" value="{{ old('merchant', $supplier->merchant) }}" placeholder="Enter merchant name"
                    required>
                  @error('merchant')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="sosmed">{{ __('Social Media') }}</label>
                  <input type="text" class="form-control @error('sosmed') is-invalid @enderror" id="sosmed"
                    name="sosmed" value="{{ old('sosmed', $supplier->sosmed) }}" placeholder="Enter social media link">
                  @error('sosmed')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="phone">{{ __('Phone') }}</label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    name="phone" value="{{ old('phone', $supplier->phone) }}" placeholder="Enter phone number">
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="whatsapp">{{ __('WhatsApp') }}</label>
                  <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp"
                    name="whatsapp" value="{{ old('whatsapp', $supplier->whatsapp) }}"
                    placeholder="Enter WhatsApp number">
                  @error('whatsapp')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                  <button type="submit" class="btn btn-primary">{{ __('Update Supplier') }}</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

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
