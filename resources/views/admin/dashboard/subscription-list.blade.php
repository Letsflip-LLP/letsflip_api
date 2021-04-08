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
          <thead>
            <tr> 
              <th>
                Email
              </th>   
              <th>
                Type
              </th>
              <th>
                Star Date
              </th>
              <th>
                End Date
              </th>
              <th>
                Action
              </th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>
              <input   placeholder="Email" name="email" class="form-control"/>
            </td> 
            <td>
              <select   placeholder="Type" name="type" class="form-control">
                <option>-- Account Type --</option>
                <option value="1">Basic</option>
                <option value="2">Private</option>
                <option value="3">Master</option>
              </select>
            </td>
            <td>
              <input   type="date" placeholder="Start Date" name="date_start" class="form-control"/>
            </td>
            <td>
              <input   type="date" placeholder="End Date" name="date_end" class="form-control"/>
            </td>
            <td>
              <button type="submit" class="btn btn-sm btn-gradient-primary btn-fw"><i class="mdi mdi-account-plus"></i>&nbsp;Invite</button>
            </td>
          </tr>
          </tbody>
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
              <input value="{{request()->input('email')}}" placeholder="Email" name="email" class="form-control"/>
            </td> 
            <td>
              <select placeholder="Type" name="type" class="form-control">
                <option value="all">-- All Type --</option>
                <option value="1" {{request()->input('type') == 1 ? 'selected' : ''}}>Basic</option>
                <option value="2" {{request()->input('type') == 2 ? 'selected' : ''}}>Private</option>
                <option value="3" {{request()->input('type') == 3 ? 'selected' : ''}}>Master</option>
              </select>
            </td>
            <td>
              <input type="date" placeholder="Start Date" name="date_start" class="form-control"/>
            </td>
            <td>
              <input type="date" placeholder="End Date" name="date_end" class="form-control"/>
            </td>
            <td>
              <select placeholder="Type" name="status" class="form-control">
                <option value="all">-- All Status --</option>
                <option value="1" {{request()->input('status') == 1 ? 'selected' : ''}}>Registered</option>
                <option value="2" {{request()->input('status') == 2 ? 'selected' : ''}}>Waiting Register</option> 
              </select>
            </td>
            <td>
              <select placeholder="Type" name="per_page" class="form-control">
                <option value="5" {{request()->input('per_page') == 5 ? 'selected' : ''}}>-- Per Page (5) --</option>
                <option value="10" {{request()->input('per_page') == 10 ? 'selected' : ''}}>Per Page 10</option>
                <option value="20" {{request()->input('per_page') == 20 ? 'selected' : ''}}>Per Page 20</option> 
                <option value="30" {{request()->input('per_page') == 30 ? 'selected' : ''}}>Per Page 30</option> 
              </select>
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
              Email
            </th>   
            <th>
              Name
            </th>  
            <th>
              Type
            </th>
            <th>
              Star Date
            </th>
            <th>
              End Date
            </th>
            <th>
              Status
            </th> 
          </tr>
        </thead>
        <tbody>
          @foreach ($subscribers as $subs)
            <tr> 
              <td>
                {{$subs->User ? $subs->User->email : $subs->email}}
              </td> 
              <td>
                {{$subs->User ? $subs->User->first_name.' '.$subs->User->last_name : 'N/A'}}
              </td>  
              <td>
                <label class="{{$subs->type == 2 ? 'badge badge-success' : ''}} {{$subs->type == 3 ? 'badge badge-warning' : ''}} {{$subs->type == 1 ? 'badge badge-secondary' : ''}}">{{subsType($subs->type)->name}}</label>
              </td>
              <td>
                 {{$subs->date_start}}
              </td>
              <td>
                  {{$subs->date_end}} 
              </td>
              <td>
                @if($subs->User )
                  <label class="badge badge-info">Accepted</label> 
                @else
                  <label class="badge badge-danger">Waiting Register</label> 
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