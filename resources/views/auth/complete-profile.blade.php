@extends('auth.layouts.master')

@section('title', 'Complete Profile')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-6 mx-auto">

          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="row mb-4 align-items-center">
                <!-- Kiri: Logo + Judul -->
                <div class="col-md-6 text-left">
                    <div class="brand-logo mb-2">
                        <img src="/images/logo.svg" alt="logo">
                    </div>
                    <h4>Complete Your Profile</h4>
                    <h6 class="font-weight-light">Fill your personal data below</h6>
                </div>

                <!-- Kanan: Avatar Preview -->
                <div class="col-md-6 text-center">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/faces/face28.jpg') }}"
                        alt="profile" class="img-fluid rounded-circle" width="100">
                </div>
            </div>


            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('profile.complete.store') }}" enctype="multipart/form-data">
              @csrf

              <!-- Avatar Preview -->

              <!-- Name -->
              <div class="form-group">
                  <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                        placeholder="Full Name" value="{{ old('name', auth()->user()->name) }}" required>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Username (readonly) -->
              <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg"
                        placeholder="Username" value="{{ auth()->user()->username }}" readonly>
              </div>

              <!-- Email (readonly) -->
              <div class="form-group">
                  <input type="text" name="email" class="form-control form-control-lg"
                        placeholder="Email" value="{{ auth()->user()->email }}" readonly>
              </div>

              <!-- Phone -->
              <div class="form-group">
                  <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                        placeholder="Phone Number" value="{{ old('phone', auth()->user()->phone) }}">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Gender -->
              <div class="form-group">
                  <select name="gender" class="form-control form-control-lg @error('gender') is-invalid @enderror">
                      <option value="">Gender</option>
                      <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                      <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                      <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Birthdate -->
              <div class="form-group">
                  <input type="date" name="birthdate" class="form-control form-control-lg @error('birthdate') is-invalid @enderror"
                        placeholder="Birthdate" value="{{ old('birthdate', auth()->user()->birthdate) }}">
                  @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Address -->
              <div class="form-group">
                  <textarea name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                            placeholder="Full Address">{{ old('address', auth()->user()->address) }}</textarea>
                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- City -->
              <div class="form-group">
                  <input type="text" name="city" class="form-control form-control-lg @error('city') is-invalid @enderror"
                        placeholder="City" value="{{ old('city', auth()->user()->city) }}">
                  @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Province -->
              <div class="form-group">
                  <input type="text" name="province" class="form-control form-control-lg @error('province') is-invalid @enderror"
                        placeholder="Province" value="{{ old('province', auth()->user()->province) }}">
                  @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Country -->
              <div class="form-group">
                  <input type="text" name="country" class="form-control form-control-lg @error('country') is-invalid @enderror"
                        placeholder="Country" value="{{ old('country', auth()->user()->country) }}">
                  @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Avatar Upload -->
              <div class="form-group">
                <label class="mb-1">Avatar (optional)</label>
                <input type="file" name="avatar" class="form-control form-control-lg @error('avatar') is-invalid @enderror">
                @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mt-3">
                <button type="submit"
                  class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn">
                  SAVE PROFILE
                </button>
              </div>

            </form>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
