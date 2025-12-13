@extends('admin.layouts.master')

@section('title', 'Detail User')

@section('content')
<div class="content-wrapper">
  <div class="row">

    {{-- Avatar --}}
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">

          <img src="{{ $user->avatar
              ? asset('storage/'.$user->avatar)
              : asset('images/faces/face28.jpg') }}"
               class="rounded-circle mb-3"
               width="150"
               height="150">

          <h5 class="mb-0">{{ $user->name ?? '-' }}</h5>
          <small class="text-muted">{{ '@'.$user->username }}</small>

          <div class="mt-3">
            <span class="badge badge-info">
              {{ $user->role->name }}
            </span>
          </div>

        </div>
      </div>
    </div>

    {{-- Detail --}}
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">

          <h4 class="card-title mb-4">Informasi User</h4>

          <table class="table table-borderless">
            <tr>
              <th width="200">Email</th>
              <td>
                {{ $user->email }}
                @if($user->email_verified_at)
                  <span class="badge badge-success ml-2">
                    <i class="fas fa-check"></i> Verified
                  </span>
                @else
                  <span class="badge badge-danger ml-2">
                    <i class="fas fa-times"></i> Not Verified
                  </span>
                @endif
              </td>
            </tr>

            <tr>
              <th>Phone</th>
              <td>
                {{ $user->phone ?? '-' }}
                @if($user->phone_verified_at)
                  <span class="badge badge-success ml-2">
                    <i class="fas fa-check"></i> Verified
                  </span>
                @else
                  <span class="badge badge-warning ml-2">
                    <i class="fas fa-clock"></i> Not Verified
                  </span>
                @endif
              </td>
            </tr>

            <tr>
              <th>Gender</th>
              <td>{{ ucfirst($user->gender ?? '-') }}</td>
            </tr>

            <tr>
              <th>Birthdate</th>
              <td>{{ $user->birthdate ?? '-' }}</td>
            </tr>

            <tr>
              <th>Address</th>
              <td>
                {{ $user->address ?? '-' }}<br>
                {{ $user->city }} {{ $user->province }} {{ $user->country }}
              </td>
            </tr>

            <tr>
              <th>Last Login</th>
              <td>
                {{ $user->last_login_at
                    ? $user->last_login_at->format('d M Y H:i')
                    : '-' }}
              </td>
            </tr>

            <tr>
              <th>Registered At</th>
              <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
          </table>

          <div class="mt-4">
            <a href="{{ route('users.edit', $user->id) }}"
               class="btn btn-warning">
              <i class="fas fa-edit"></i> Edit
            </a>

            <a href="{{ route('users.index') }}"
               class="btn btn-light">
              Kembali
            </a>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection
