@extends('auth.layouts.master')

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
                <input type="text" name="name" class="form-control form-control-lg"
                  placeholder="Full Name" required>
              </div>

              <div class="form-group">
                <input type="text" name="phone" class="form-control form-control-lg"
                  placeholder="Phone Number">
              </div>

              <div class="form-group">
                <select name="gender" class="form-control form-control-lg">
                  <option value="">Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="form-group">
                <input type="date" name="birthdate" class="form-control form-control-lg"
                  placeholder="Birthdate">
              </div>

              <div class="form-group">
                <textarea name="address" class="form-control form-control-lg"
                  placeholder="Full Address"></textarea>
              </div>

              <div class="form-group">
                <input type="text" name="city" class="form-control form-control-lg"
                  placeholder="City">
              </div>

              <div class="form-group">
                <input type="text" name="province" class="form-control form-control-lg"
                  placeholder="Province">
              </div>

              <div class="form-group">
                <input type="text" name="country" class="form-control form-control-lg"
                  placeholder="Country">
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
