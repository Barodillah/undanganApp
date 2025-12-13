@extends('auth.layouts.master')

@section('title', 'Register')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">

          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo">
                <a href="/">
                  <img src="../../images/logo.svg" alt="logo">
                </a>
            </div>

            <h4>New here?</h4>
            <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>

            <form class="pt-3" method="POST" action="{{ route('register.store') }}">
              @csrf

              <div class="form-group">
                  <input type="text" name="username"
                        class="form-control form-control-lg @error('username') is-invalid @enderror"
                        placeholder="Username"
                        value="{{ old('username') }}" required>
                  @error('username')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="email" name="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}" required>
                  @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="password" name="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="Password" required>
                  @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="form-group">
                  <input type="password" name="password_confirmation"
                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm Password" required>
                  @error('password_confirmation')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>


              <div class="mb-4">
                <div class="form-check">
                  <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" required>
                    I agree to all Terms & Conditions
                  </label>
                </div>
              </div>

              <div class="mt-3">
                <button type="submit"
                  class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                  SIGN UP
                </button>
              </div>

              <div class="text-center mt-4 font-weight-light">
                Already have an account?
                <a href="/login" class="text-primary">Login</a>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
