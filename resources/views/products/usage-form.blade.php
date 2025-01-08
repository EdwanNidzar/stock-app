@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Product Usage') }}</h1>
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
            <div class="card-body p-4">
              <form action="{{ route('product.usage') }}" method="GET" class="form-row" id="usage-form">
                <!-- Remove product selection -->
                <div class="form-group col-md-6">
                  <label for="start_date" class="form-label">Tanggal Mulai</label>
                  <input type="date" id="start_date" name="start_date"
                    class="form-control @error('start_date') is-invalid @enderror" required>
                  @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group col-md-6">
                  <label for="end_date" class="form-label">Tanggal Selesai</label>
                  <input type="date" id="end_date" name="end_date"
                    class="form-control @error('end_date') is-invalid @enderror" required>
                  @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </form>

              <button type="submit" class="btn btn-primary mt-3" form="usage-form">Lihat Penggunaan</button>
            </div>
            <!-- /.card-body -->

          </div>

        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
@endsection
