@extends ('admin.layout',array())
@section('wrapper')

<div class="row">

  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit</h4>
        {{-- <p class="card-description"> Basic form layout </p> --}}
        <br>
        <form class="forms-sample" method="POST">

          {{ csrf_field() }}

          <div class="form-group">
            <label for="exampleInputUsername1">Email</label>
            <input disabled value="{{$subscriber->User ? $subscriber->User->email : $subscriber->email}}" type="email" required placeholder="Email" name="email" class="form-control"/>
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Company</label>
            <select name="company_id" placeholder="Company" name="company" class="form-control">
              <option value="NULL">-- Company --</option>
              @foreach ($company as $com)
                <option {{ ($subscriber->User && $subscriber->User->company_id == $com->id) || $subscriber->company_id == $com->id ? 'selected' : ''}} value={{$com->id}}>{{$com->title}}</option>
              @endforeach
            </select>
          </div>


          <div class="form-group">
            <label for="exampleInputEmail1">Account Type </label>
            <select  required placeholder="Type" name="type" class="form-control">
              <option>-- Account Type --</option>
              <option value="1" {{$subscriber->type == 1 ? "selected"  : ""}}>Basic</option>
              <option value="2" {{$subscriber->type == 2 ? "selected"  : ""}}>Private</option>
              <option value="3" {{$subscriber->type == 3 ? "selected"  : ""}}>Master</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Environment </label>
            <select  required placeholder="Environment" name="environment" class="form-control">
              <option>-- Environment --</option>
              <option value="staging" {{$subscriber->environment == 'staging' ? "selected"  : ""}}>Staging</option>
              <option value="production" {{$subscriber->environment == 'production' ? "selected"  : ""}}>Production</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Validity Start Date</label>
            <input value={{$subscriber->date_start}} required  type="date" placeholder="Start Date" name="date_start" class="form-control"/>
            <input value={{$subscriber->id}} required  type="hidden" placeholder="Start Date" name="id" class="form-control"/>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Validity End Date</label>
            <input value={{$subscriber->date_end}} required  type="date" placeholder="End Date" name="date_end" class="form-control"/>
          </div>
          <div class="form-check form-check-flat form-check-primary">
            <label class="form-check-label">
              <input {{$subscriber->is_creator ? "checked" : ""}} name="is_creator" type="checkbox" class="form-check-input"> Is Creator <i class="input-helper"></i></label>
          </div>
          <br>
          <a class="btn btn-light" href={{url('admin/user/subscribers')}}>Cancel</a>
          <button type="submit" class="btn btn-gradient-primary mr-2">Edit</button>
         </form>
      </div>
    </div>
  </div>
</div>

@endsection
