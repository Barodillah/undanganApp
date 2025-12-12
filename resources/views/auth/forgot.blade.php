@extends('auth.layouts.master')

@section('title', 'Forgot Password')

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left py-5 px-4 px-sm-5">

            <div class="brand-logo">
              <img src="../../images/logo.svg" alt="logo">
            </div>

            <h4>Lupa Password?</h4>
            <h6 class="font-weight-light">Masukkan email untuk menerima kode OTP.</h6>

            <form class="pt-3" method="POST" action="{{ route('forgot.send') }}">
              @csrf

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              @if (session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              <div class="form-group">
                <input 
                  type="email" 
                  name="email" 
                  class="form-control form-control-lg" 
                  placeholder="Email" 
                  required
                >
              </div>

              <div class="mt-3">
                <button 
                  type="submit" 
                  class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                >
                  KIRIM OTP
                </button>
              </div>

              <div class="text-center mt-4 font-weight-light">
                Kembali ke login? 
                <a href="/login" class="text-primary">Login</a>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
@endsection
