@extends ('admin.layout',array())
@section('wrapper')

<style>
    .form-text {
        position: relative;
    }

    .static-value {
        border-left: 2px solid black;
        padding: 0 7px;
        position: absolute;
        right: 40px;
        top: 10px;
        font-weight: bold;
        color: black;
    }
</style>

<div class="card col-md-6">
    <div class="card-body">
        <form class="forms-sample" method="POST" action="{{url('admin/system/prices/edit')}}">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$prices->id}}">
            <div class="form-group">
                <label for="place_group_vendor">Price Group Vendor</label>
                <input type="text" name="price_group_vendor" class="form-control" value="{{$prices->price_group_vendor}}" />
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="{{$prices->title}}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" name="description" value="{{$prices->description}}">
            </div>
            <div class="form-group">
                <label for="sgd">SGD</label>
                <div class="form-text">
                    <input type="number" step="0.01" class="form-control" name="sgd" value="{{$prices->sgd}}">
                    <label for="sgd" class="static-value">SGD</label>
                </div>
            </div>
            <div class="form-group">
                <label for="usd">USD</label>
                <div class="form-text">
                    <input type="number" step="0.01" class="form-control" name="usd" value="{{$prices->usd}}">
                    <label for="usd" class="static-value">USD</label>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" name="status" value="{{$prices->status}}">
            </div>

            <button type="submit" class="float-right btn btn-gradient-primary mr-2 ml-3">Edit</button>
            <a class="btn btn-light float-right" href={{url('admin/system/prices')}}>Cancel</a>
        </form>
    </div>
</div>

@endsection