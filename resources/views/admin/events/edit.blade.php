@extends('admin.layouts.master')

@section('title', 'Edit Event')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Event</h4>

      <form action="{{ route('events.update', $event->slug) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" name="title" class="form-control"
                 value="{{ $event->title }}" required>
        </div>

        <div class="form-group">
          <label>Tanggal Event</label>
          <input type="date" name="event_date"
                 value="{{ $event->event_date->format('Y-m-d') }}"
                 class="form-control" required>
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="description"
                    class="form-control">{{ $event->description }}</textarea>
        </div>

        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ $event->status == 'published' ? 'selected' : '' }}>Published</option>
            <option value="cancelled" {{ $event->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('events.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
