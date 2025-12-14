@extends('admin.layouts.master')

@section('title', 'Edit Visitor')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Visitor</h4>

      <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Acara</label>
          <select name="event_id" class="form-control" required>
            @foreach($events as $event)
              <option value="{{ $event->id }}"
                @selected($visitor->event_id == $event->id)>
                {{ $event->title }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>Nama Visitor</label>
          <input type="text"
                 name="name"
                 class="form-control"
                 value="{{ $visitor->name }}"
                 required>
        </div>

        <div class="form-group">
          <label>Alamat</label>
          <textarea name="address"
                    class="form-control">{{ $visitor->address }}</textarea>
        </div>

        <div class="form-group">
          <label>Status Kehadiran</label>
          <select name="attendance_status" class="form-control">
            <option value="pending" @selected($visitor->attendance_status=='pending')>Pending</option>
            <option value="hadir" @selected($visitor->attendance_status=='hadir')>Hadir</option>
            <option value="tidak_hadir" @selected($visitor->attendance_status=='tidak_hadir')>Tidak Hadir</option>
          </select>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('visitors.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
