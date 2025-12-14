@extends('admin.layouts.master')

@section('title', 'Tambah Visitor')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Tambah Visitor</h4>

      <form action="{{ route('visitors.store') }}" method="POST">
        @csrf

        <div class="form-group">
        <label>Acara</label>

        @if(isset($selectedEvent))
            {{-- MODE EVENT TERKUNCI --}}
            <input type="hidden" name="event_id" value="{{ $selectedEvent->id }}">
            <input type="text"
                class="form-control"
                value="{{ $selectedEvent->title }}"
                readonly>
        @else
            {{-- MODE NORMAL --}}
            <select name="event_id" class="form-control" required>
            <option value="">-- Pilih Acara --</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->title }}</option>
            @endforeach
            </select>
        @endif
        </div>


        <div class="form-group">
          <label>Nama Visitor</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Alamat</label>
          <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="form-group">
          <label>Status Kehadiran</label>
          <select name="attendance_status" class="form-control">
            <option value="pending">Pending</option>
            <option value="hadir">Hadir</option>
            <option value="tidak_hadir">Tidak Hadir</option>
          </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('visitors.index') }}" class="btn btn-light">Kembali</a>
      </form>

    </div>
  </div>
</div>
@endsection
