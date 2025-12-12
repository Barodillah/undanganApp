@extends('auth.layouts.master')

@section('title', 'Reset Password')

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

                        <h4>Reset Your Password</h4>
                        <h6 class="font-weight-light">Create your new password.</h6>

                        <form class="pt-3" method="POST" action="{{ route('forgot.reset.post') }}">
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

                            {{-- email dari session --}}
                            <input type="hidden" name="email" value="{{ session('reset_email') }}">

                            <div class="form-group">
                                <input type="password"
                                    name="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="New Password"
                                    required
                                    minlength="6">

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm New Password"
                                    required
                                    minlength="6">

                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                    RESET PASSWORD
                                </button>
                            </div>

                            <div class="text-center mt-4 font-weight-light">
                                Remember your password? <a href="/login" class="text-primary">Login</a>
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
