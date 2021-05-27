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

        <form method="POST" action="{{url('admin/company/add')}}">
            {{ csrf_field() }}
            <table class="table table-striped"> 
            <thead>
                <tr> 
                    <th>
                        Company Name
                    </th>   
                    <th>
                        Description
                    </th>
                    <th>
                        Address
                    </th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input required type="text" required placeholder="Company name" name="title" class="form-control"/>
                </td>  
                <td>
                    <input required type="text" required placeholder="Company description" name="text" class="form-control"/>
                </td> 
                <td>
                    <input required type="text" required placeholder="Company address" name="address" class="form-control"/>
                </td> 
                <td>
                <button type="submit" class="btn btn-sm btn-gradient-primary btn-fw"><i class="mdi mdi-account-plus"></i>&nbsp;ADD</button>
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
    <div class="card-body">
          
        <table class="table table-striped">
          <thead>
            <tr>
              <th> #ID </th>
              <th> Company Name </th>
              <th> Total User </th>
              <th> Address </th>
              <th> Created at </th>
              <th> Action </th>
            </tr>
          </thead>
          <tbody>
            @php
            $i = 1;    
            @endphp
            @foreach ($company as $com)
                <tr> 
                    <td>
                        {{$com->id}}
                    </td>
                    <td>
                        {{$com->title}}
                    </td>
                    <td>
                        {{$com->Users->count()}}
                    </td>
                    <td> 
                        {{$com->address}}
                    </td>
                    <td> 
                        {{$com->created_at}}
                    </td>
                    <td>
                        <a href="{{url('/admin/company/edit/'.$com->id)}}" class="badge badge-success text-dark">Edit</a>   
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div> 
    </div>  
</div> 
 
@endsection