@extends('layouts.app')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ __('Users') }}</h1>
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
            <div class="card-body p-0">

              <div class="mb-3 text-right p-3">
                <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>
                        {{ $user->roles->pluck('name')->join(', ') }}
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-3">
                          <!-- Update Role Form -->
                          <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-0">
                              <select name="role" class="form-control" onchange="this.form.submit()">
                                @foreach ($roles as $role)
                                  <option value="{{ $role->name }}"
                                    {{ $user->roles->pluck('name')->contains($role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </form>

                          <!-- Delete User Form -->
                          <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger ms-3"
                              onclick="return confirm('Are you sure you want to delete this user?')">
                              Delete
                            </button>
                          </form>
                        </div>
                      </td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix">
              {{ $users->links() }}
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
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
