@extends ('admin.layout',array())
@section('wrapper')
 
@php
    // dd($company->title);
@endphp

<div class="card col-md-6">
    <div class="card-body"> 
      <form class="forms-sample" method="POST" action="{{url('admin/company/submit-edit')}}">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="exampleInputUsername1">Company Name</label>
          <input type="text" class="form-control" placeholder="Company name" required name="title" value="{{$company->title}}">
          <input type="hidden" class="form-control" required name="id" value={{$company->id}}>
        </div>
        <div class="form-group">
            <label for="exampleInputUsername1">Company Description</label>
            <input type="text" class="form-control" placeholder="Company name" required name="text" value="{{$company->text}}">
        </div>
        <div class="form-group">
            <label for="exampleInputUsername1">Company Address</label>
            <input type="text" class="form-control" placeholder="Company name" required name="address" value="{{$company->address}}">
        </div>
        
        <button type="submit" class="float-right btn btn-gradient-primary mr-2 ml-3">Edit</button>
        <a class="btn btn-light float-right" href={{url('admin/company/list')}}>Cancel</a>
      </form>
    </div>
</div>
 
@endsection