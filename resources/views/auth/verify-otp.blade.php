@extends('auth.layouts.master')

@section('title', 'Verify OTP')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">

          <div class="auth-form-light text-left py-5 px-4 px-sm-5 text-center">
            <div class="brand-logo mb-4">
              <img src="/images/logo.svg" alt="logo">
            </div>

            <h4>Email Verification</h4>
            <p class="font-weight-light mb-4">
              Enter the 6-digit code we sent to:
              <br>
              <strong>{{ $email }}</strong>
            </p>

            <form method="POST" action="{{ route('otp.verify') }}" class="pt-3">
              @csrf

              <input type="hidden" name="email" value="{{ $email }}">

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif


              <div class="form-group d-flex justify-content-center">
                <input type="text" name="code"
                  class="form-control form-control-lg text-center"
                  style="letter-spacing: 8px; font-size: 24px;"
                  maxlength="6" placeholder="______" required>
              </div>

              <div class="mt-3">
                <button type="submit"
                  class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                  VERIFY
                </button>
              </div>

              <div class="text-center mt-4 font-weight-light">
                Didn’t receive the code?
                <a href="{{ route('otp.resend', ['email' => $email]) }}" class="text-primary">Resend</a>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
