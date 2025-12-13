@extends('admin.layouts.master')

@section('title', 'Tambah Event')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Tambah Event</h4>

      <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Tanggal Event</label>
          <input type="date" name="event_date" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('events.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
