@extends('admin.layouts.master')

@section('title', 'Complete Profile')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-6 mx-auto">

          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo">
              <img src="/images/logo.svg" alt="logo">
            </div>

            <h4>Complete Your Profile</h4>
            <h6 class="font-weight-light mb-4">Fill your personal data below</h6>

            <form method="POST" action="{{ route('profile.complete.store') }}" enctype="multipart/form-data">

              @csrf

              <div class="form-group">
                  <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                        placeholder="Full Name" value="{{ old('name', auth()->user()->name) }}" required>
                  @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                        placeholder="Phone Number" value="{{ old('phone', auth()->user()->phone) }}">
                  @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <select name="gender" class="form-control form-control-lg @error('gender') is-invalid @enderror">
                      <option value="">Gender</option>
                      <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                      <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                      <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('gender')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="date" name="birthdate" class="form-control form-control-lg @error('birthdate') is-invalid @enderror"
                        placeholder="Birthdate" value="{{ old('birthdate', auth()->user()->birthdate) }}">
                  @error('birthdate')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <textarea name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                            placeholder="Full Address">{{ old('address', auth()->user()->address) }}</textarea>
                  @error('address')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="text" name="city" class="form-control form-control-lg @error('city') is-invalid @enderror"
                        placeholder="City" value="{{ old('city', auth()->user()->city) }}">
                  @error('city')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="text" name="province" class="form-control form-control-lg @error('province') is-invalid @enderror"
                        placeholder="Province" value="{{ old('province', auth()->user()->province) }}">
                  @error('province')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="text" name="country" class="form-control form-control-lg @error('country') is-invalid @enderror"
                        placeholder="Country" value="{{ old('country', auth()->user()->country) }}">
                  @error('country')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>


              <div class="form-group">
                <label class="mb-1">Avatar (optional)</label>
                <input type="file" name="avatar" class="form-control form-control-lg">
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
