@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-hader">
      {{-- <button type="button" class="btn btn-gradient-primary btn-rounded btn-fw">
        <i class="mdi-large mdi mdi-account-multiple-plus"></i><br/>Invite</button> --}}

      @if($errors->any())
      <p class="text-danger">{{$errors->first()}}</p>
      @endif

      <form method="POST" action="{{url('admin/user/subscribers')}}">
        {{ csrf_field() }}
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>
                <label for="email">Email</label>
                <input type="email" required placeholder="Email" name="email" class="form-control" />
              </td>
              <td>
                <label for="company_id">Company</label>
                <select required placeholder="Type" name="company_id" class="form-control">
                  <option value="NULL">-- Company --</option>
                  @foreach ($companies as $com)
                  <option value={{$com->id}}>{{$com->title}}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <label for="type">Type</label>
                <select required placeholder="Type" name="type" class="form-control">
                  <option>-- Account Type --</option>
                  <option value="1">Basic</option>
                  <option selected value="2">Private</option>
                  <option value="3">Master</option>
                </select>
              </td>
              <td>
                <label for="environment">Environment</label>
                <select required placeholder="Environment" name="environment" class="form-control">
                  <option>-- Environment --</option>
                  <option value="staging">Staging</option>
                  <option value="production">Production</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="table table-striped">
          <tr>
            <tbody>
              <td>
                <label for="date_start">Start Date</label>
                <input value={{$default['start_date']}} required type="date" placeholder="Start Date" name="date_start" class="form-control" />
              </td>
              <td>
                <label for="date_end">End Date</label>
                <input value={{$default['end_date']}} required type="date" placeholder="End Date" name="date_end" class="form-control" />
              </td>
              <td>
                <input type="checkbox" placeholder="Start Date" checked value="true" name="is_creator" class="form-control" />
              </td>
              <td>
                <button type="submit" class="btn btn-sm btn-gradient-primary btn-fw"><i class="mdi mdi-account-plus"></i>&nbsp;Invite</button>
              </td>
            </tbody>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-hader">
    </div>
    <div class="card-body">
      <form method="GET" action="{{url('admin/user/subscribers')}}">
        {{ csrf_field() }}
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>
                <label for="email">Email</label>
                <input list="email" type="text" placeholder="{{request()->filled('email') ? request()->input('email') : '-- All Email --'}}" autocomplete="off" name="email" class="form-control" />
                <datalist id="email">
                  <option value="-- All Email --" hidden></option>
                  @foreach($emails as $email)
                  <option value="{{$email->email}}"></option>
                  @endforeach
                </datalist>
              </td>
              <td>
                <label for="company_id">Company</label>
                <select required placeholder="Type" name="company_id" class="form-control">
                  <option selected value="NULL">-- All Company --</option>
                  @foreach ($companies as $com)
                  <option {{request()->input('company_id') == $com->id ? 'selected' : ''}} value={{$com->id}}>{{$com->title}}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <label for="type">Type</label>
                <select placeholder="Type" name="type" class="form-control">
                  <option value="">-- All Type --</option>
                  <option value="1" {{request()->input('type') == 1 ? 'selected' : ''}}>Basic</option>
                  <option value="2" {{request()->input('type') == 2 ? 'selected' : ''}}>Private</option>
                  <option value="3" {{request()->input('type') == 3 ? 'selected' : ''}}>Master</option>
                </select>
              </td>
              <td>
                <label for="environment">Environment</label>
                <select placeholder="Environment" name="environment" class="form-control">
                  <option value="">-- All Environment --</option>
                  <option value="staging" {{request()->input('environment') == 'staging' ? 'selected' : ''}}>Staging</option>
                  <option value="production" {{request()->input('environment') == 'production' ? 'selected' : ''}}>Production</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>
                <label for="status">Status</label>
                <select name="status" class="form-control">
                  <option value="all">-- All Status --</option>
                  <option value="1" {{request()->input('status') == 1 ? 'selected' : ''}}>Registered</option>
                  <option value="2" {{request()->input('status') == 2 ? 'selected' : ''}}>Waiting Register</option>
                </select>
              </td>
              <td>
                <label for="per_page">Per Page</label>
                <select placeholder="Type" name="per_page" class="form-control">
                  <option value="10" {{request()->input('per_page') == 10 ? 'selected' : ''}}>-- Per Page (10) --</option>
                  <option value="15" selected>-- Per Page (15) --</option>
                  <option value="20" {{request()->input('per_page') == 20 ? 'selected' : ''}}>-- Per Page (20) --</option>
                  <option value="30" {{request()->input('per_page') == 30 ? 'selected' : ''}}>-- Per Page (30) --</option>
                  <option value="50" {{request()->input('per_page') == 50 ? 'selected' : ''}}>-- Per Page (50) --</option>
                  <option value="100" {{request()->input('per_page') == 100 ? 'selected' : ''}}>-- Per Page (100) --</option>
                </select>
              </td>
              <td>
                <label for="date_start">Start Date</label>
                <input type="date" placeholder="Start Date" name="date_start" class="form-control" />
              </td>
              <td>
                <label for="date_end">End Date</label>
                <input type="date" placeholder="End Date" name="date_end" class="form-control" />
              </td>
              <td>
                <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw"><i class="mdi mdi-account-search"></i>&nbsp;Search</button>
              </td>
            </tr>
          </tbody>
        </table>
      </form>

      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              #
            </th>
            <th>
              Email
            </th>
            <th>
              Name
            </th>
            <th>
              Type
            </th>
            <th>
              Environment
            </th>
            <th>
              Start Date
            </th>
            <th>
              End Date
            </th>
            <th>
              Is Creator
            </th>
            <th>
              Status
            </th>
            <th>
              Action
            </th>
          </tr>
        </thead>
        <tbody>
          @php
          $j = 1;
          @endphp
          @foreach ($subscribers as $subs)
          <tr>
            <td>
              {{$j++}}
            </td>
            <td>
              {{$subs->User ? $subs->User->email : $subs->email}}
            </td>
            <td>
              {{$subs->User ? $subs->User->first_name.' '.$subs->User->last_name : 'N/A'}}
            </td>
            <td>
              <label class="{{$subs->type == 2 ? 'badge badge-success' : ''}} {{$subs->type == 3 ? 'badge badge-warning text-dark' : ''}} {{$subs->type == 1 ? 'badge badge-secondary' : ''}}">{{subsType($subs->type)->name}}</label>
            </td>
            <td>
              {{$subs->environment}}
            </td>
            <td>
              {{$subs->date_start}}
            </td>
            <td>
              {{$subs->date_end}}
            </td>
            <td>
              @if($subs->is_creator == true) <label class="badge badge-info">Yes</label> @else <label class="badge badge-warning text-dark">No</label> @endif
            </td>
            <td>
              @if($subs->User && $subs->status == 1)
              <label class="badge badge-info">Accepted</label>
              @elseif($subs->User && $subs->status == 2)
              <label class="badge badge-warning text-dark">Awaiting Approval</label>
              @elseif(!$subs->User)
              <label class="badge badge-danger text-dark">Waiting Register</label>
              @endif
            </td>
            <td>
              <a href="{{url('/admin/user/subscribers/edit/'.$subs->id)}}" class="badge badge-success text-dark">Edit</a>
              @if ($subs->email)
              <a href="{{url('/admin/user/subscribers/resend-invitation/'.$subs->id)}}" class="badge badge-warning text-dark">Resend Invitation</a>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div style="margin-top : 20">
        {{ $subscribers->appends(request()->input())->links("pagination::bootstrap-4") }}
      </div>

    </div>
  </div>
</div>

@endsection