@extends ('admin.layout',array())
@section('wrapper')

<div class="row">

  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit</h4>
        {{-- <p class="card-description"> Basic form layout </p> --}}
        <br>
        <form class="forms-sample" method="POST" action="{{url('admin/user/users/edit')}}">

          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{$user->id}}">
          <div class="form-group">
            <label for="email">Email</label>
            <input disabled value="{{$user->email}}" type="email" required placeholder="Email" name="email" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="company">Company</label>
            <select name="company_id" placeholder="Company" name="company" class="form-control">
              <option value="">-- Unregistered to any company --</option>
              @foreach ($company as $com)
                <option {{ ( $user->company_id == $com->id) || $user->company_id == $com->id ? 'selected' : ''}} value={{$com->id}}>{{$com->title}}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="name">Name</label>
            <input disabled value="{{$user->first_name}}" type="text" required name="name" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="name">Name</label>
            <input disabled value="{{$user->last_name}}" type="text" required name="name" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input disabled value="{{$user->username}}" type="text" required name="username" class="form-control"/>
          </div>

          <div class="form-check form-check-flat form-check-primary">
            <label class="form-check-label">
              <input {{$user->is_admin == 1 ? "checked" : ""}} name="is_admin" type="checkbox" class="form-check-input"> Is Admin <i class="input-helper"></i></label>
          </div>
          <br>

          <div class="form-check form-check-flat form-check-primary">
            <label class="form-check-label">
              <input {{$user->email_verified_at != NULL ? "checked" : ""}} name="is_verified" type="checkbox" class="form-check-input"> Is Verified <i class="input-helper"></i></label>
          </div>

          <a class="btn btn-light" href={{url('admin/user/users')}}>Cancel</a>
          <button type="submit" class="btn btn-gradient-primary mr-2">Edit</button>
         </form>
      </div>
    </div>
  </div>
</div>

@endsection
