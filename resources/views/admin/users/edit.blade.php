@extends('admin.layouts.master')

@section('title', 'Edit User')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit User</h4>

      <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="name"
                 value="{{ $user->name }}"
                 class="form-control" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email"
                 value="{{ $user->email }}"
                 class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ $user->role_id == $role->id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">
            Hanya Super Admin yang dapat mengubah role user
            </small>
        </div>


        <button class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
