<!DOCTYPE html>
<html>
  @include('layouts.admin_layouts.head')
  <body>
    <div class="login-page">
      <div class="container d-flex align-items-center position-relative py-5">
        <div class="card shadow-sm w-100 rounded overflow-hidden bg-none">
          <div class="card-body p-0">
            <div class="row gx-0 align-items-stretch">
              <!-- Logo & Information Panel-->
              <div class="col-lg-6">
                <div class="info d-flex justify-content-center flex-column p-4 h-100">
                  <div class="py-5">
                    <h1 class="display-6 fw-bold">SUPER ADMIN</h1>
                    <p class="fw-light mb-0">Please Login With Email & Password</p>
                  </div>
                </div>
              </div>
              <!-- Form Panel    -->
              <div class="col-lg-6 bg-white">

                <div class="d-flex align-items-center px-4 px-lg-5 h-100 bg-dash-dark-2">
                  <form class="login-form py-5 w-100" method="post" action="{{ url('/admin/login') }}">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @csrf
                    <div class="input-material-group mb-3">
                    <select class="input-material" name="role">
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Moderator">Moderator</option>
                    </select>
                    </div>
                    <div class="input-material-group mb-3">
                      <input class="input-material" type="email" name="email" >
                      <label class="label-material" for="login-username">Email</label>
                    </div>
                    <div class="input-material-group mb-4">
                      <input class="input-material" type="password" name="password" >
                      <label class="label-material" for="login-password">Password</label>
                    </div>
                    <button class="btn btn-primary mb-3" id="login" type="submit">Login</button><br><a class="text-sm text-paleBlue" href="#">Forgot Password?</a><br>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@include('layouts.admin_layouts.scripts')
</body>
</html>
