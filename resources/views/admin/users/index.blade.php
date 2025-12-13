@extends('admin.layouts.master')

@section('title', 'Users')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-body">

      <div class="d-flex justify-content-between mb-3">
        <h4 class="card-title">Manajemen User</h4>

        <a href="{{ route('users.create') }}"
           class="btn btn-primary btn-sm">
          <i class="fas fa-user-plus mr-2"></i>x`Tambah User
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Avatar</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Status</th>
              <th width="140">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>

              <td>
                <img src="{{ $user->avatar
                    ? asset('storage/' . $user->avatar)
                    : asset('images/faces/face28.jpg') }}"
                     alt="avatar"
                     class="rounded-circle"
                     width="40"
                     height="40">
              </td>

              <td>{{ $user->name }}</td>
              <td>
                <a href="{{ route('users.show', $user->id) }}"
                class="text-decoration-none text-primary"
                title="Detail">
                {{ $user->username ?? '-' }}
                </a>
              </td>
              <td>
                {{ $user->email }}
              </td>

              <td>{{ $user->phone ?? '-' }}</td>

              <td>
                <span class="badge badge-info">
                  {{ $user->role->name ?? '-' }}
                </span>
              </td>

              <td>
                @if($user->email_verified_at)
                  <span class="text-success">
                    <i class="fas fa-shield-check"></i> Verified
                  </span>
                @else
                  <span class="text-danger">
                    <i class="fas fa-shield-exclamation"></i> Unverified
                  </span>
                @endif
              </td>

              <td>
                {{-- Edit (tetap tampil) --}}
                <a href="{{ route('users.edit', $user->id) }}"
                    class="btn btn-warning btn-sm"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                </a>

                {{-- Hapus hanya untuk role Admin (role_id = 2) --}}
                @if($user->role_id == 2)
                    <form action="{{ route('users.destroy', $user->id) }}"
                        method="POST"
                        class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    </form>
                @endif
                </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center">
                <i class="fas fa-users-slash"></i> Belum ada user
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function () {
        const form = this.closest('form');

        Swal.fire({
          title: 'Yakin hapus user?',
          text: 'Data user tidak bisa dikembalikan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
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
