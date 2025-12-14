@extends('admin.layouts.master')

@section('title', 'Events')

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">

          <div class="d-flex justify-content-between mb-3">
            <h4 class="card-title">Data Events</h4>
            @if(auth()->user()->role_id != 2)
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-calendar-plus mr-2"></i>Tambah Event
            </a>
            @endif
          </div>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Event</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  @if(auth()->user()->role_id != 2)
                  <th width="150">Aksi</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @forelse($events as $event)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <a href="{{ route('events.show', $event->slug) }}"
                    class="text-decoration-none text-primary"
                    title="Detail">
                    {{ $event->title }}
                    </a>
                  </td>
                  <td>{{ $event->event_date->format('d M Y') }}</td>
                  <td>
                    <label class="badge badge-{{ 
                      $event->status == 'published' ? 'success' :
                      ($event->status == 'draft' ? 'secondary' : 'danger')
                    }}">
                      {{ ucfirst($event->status) }}
                    </label>
                  </td>
                  <td>
                    <a href="{{ route('visitors.byEvent', $event->slug) }}"
                        class="btn btn-info btn-sm"
                        title="Visitors">
                        <i class="fas fa-users"></i>
                    </a>
                    @if(auth()->user()->role_id != 2 &&
                        (auth()->user()->role_id != 3 || $event->user_id == auth()->id()))
                    <a href="/check-in?acara={{ $event->slug }}"
                        class="btn btn-secondary btn-sm"
                        title="Visitors">
                        <i class="fas fa-qrcode"></i>
                    </a>
                    
                    <a href="{{ route('events.edit', $event->slug) }}"
                        class="btn btn-warning btn-sm"
                        title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('events.destroy', $event->slug) }}"
                        method="POST"
                        class="d-inline delete-event-form">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                class="btn btn-danger btn-sm btn-delete-event"
                                title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    @endif
                    </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada event</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-event').forEach(button => {
      button.addEventListener('click', function () {
        const form = this.closest('.delete-event-form');

        Swal.fire({
            title: 'Hapus Event?',
            text: 'Event yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: {
                icon: 'mt-4'
            }
            }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                Swal.showLoading();
                form.submit();
                }
            });
            }
        });
      });
    });
  });
</script>
@endsection
