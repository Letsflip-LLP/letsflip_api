@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-hader"> 
      <button type="button" class="btn btn-gradient-primary btn-rounded btn-fw">
        <i class="mdi-large mdi mdi-account-multiple-plus"></i><br/>Invite</button>
    </div>
    <div class="card-body">
      
      <table class="table table-striped">
        <thead>
          <tr> 
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
          </tr>
        </thead>
        <tbody>

          @foreach ($subscribers as $subs)
            <tr> 
              <td>
                {{$subs->User ? $subs->User->first_name.' '.$subs->User->last_name : 'N/A'}}
              </td> 
              <td>
                <label class="{{$subs->type == 2 ? 'badge badge-success' : ''}} {{$subs->type == 3 ? 'badge badge-warning' : ''}}">{{subsType($subs->type)->name}}</label>
              </td>
              <td>
                 {{$subs->date_start}}
              </td>
              <td>
                  {{$subs->date_end}} 
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