@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-hader"> 
      {{-- <button type="button" class="btn btn-gradient-primary btn-rounded btn-fw">
        <i class="mdi-large mdi mdi-account-multiple-plus"></i><br/>Invite</button> --}}
      
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
              <input required placeholder="Email" name="email" class="form-control"/>
            </td> 
            <td>
              <select required placeholder="Type" name="type" class="form-control">
                <option>-- Account Type --</option>
                <option value="1">Basic</option>
                <option value="2">Private</option>
                <option value="3">Master</option>
              </select>
            </td>
            <td>
              <input required type="date" placeholder="Start Date" name="date_start" class="form-control"/>
            </td>
            <td>
              <input required type="date" placeholder="End Date" name="date_end" class="form-control"/>
            </td>
            <td>
              <button type="submit" class="btn btn-sm btn-gradient-primary btn-fw">Invite</button>
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
      {{ $subscribers->links("pagination::bootstrap-4") }}
    </div>

  </div> 
  </div>
</div>
  
@endsection