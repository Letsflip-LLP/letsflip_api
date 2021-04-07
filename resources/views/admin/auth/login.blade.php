@extends ('admin.meta',array())
@section('content')

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
      <div class="row w-100">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left p-5"> 
            <h4>Hello! Welcome Back</h4>
            <h6 class="font-weight-light">Sign in to continue.</h6>
            <form action="{{url('admin/auth/login')}}" class="pt-3" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <input name="email" type="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email">
              </div>
              <div class="form-group">
                <input name="password" type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" href="../../index.html">SIGN IN</button>
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

