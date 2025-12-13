@extends('admin.layouts.master')

@section('title', 'Tambah User')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Tambah Admin</h4>

      <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
        <label>Username</label>
        <input type="text"
                name="username"
                class="form-control"
                required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" name="password_confirmation"
                 class="form-control" required>
        </div>

        <div class="alert alert-info">
          User yang dibuat otomatis memiliki role <strong>Admin</strong>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
