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

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

            <!-- ADDING ENGINE -->
            <!-- ================================================================================================ -->

            <form method="POST" action="{{url('admin/system/prices/add')}}">
                {{ csrf_field() }}
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <label for="price_group_vendor">Price Group Vendor</label>
                                <input required type="text" required placeholder="Price Group Vendor" name="price_group_vendor" class="form-control" />
                            </td>
                            <td>
                                <label for="title">Title</label>
                                <input required type="text" required placeholder="Title" name="title" class="form-control" />
                            </td>
                            <td>
                                <label for="description">Description</label>
                                <input required type="text" required placeholder="Description" name="description" class="form-control" />
                            </td>
                            <td>
                                <label for="status">Status</label>
                                <input required type="text" required placeholder="Status" name="status" class="form-control" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <label for="sgd">SGD</label>
                                <div class="form-text">
                                    <input type="number" step="0.01" class="form-control" name="sgd">
                                    <label for="sgd" class="static-value">SGD</label>
                                </div>
                            </td>
                            <td>
                                <label for="usd">USD</label>
                                <div class="form-text">
                                    <input type="number" step="0.01" class="form-control" name="usd">
                                    <label for="usd" class="static-value">USD</label>
                                </div>
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
        <div class="card-hader">
        </div>
        <div class="card-body">

            <!-- SEARCH ENGINE -->
            <!-- ================================================================================================ -->

            <form method="GET" action="{{url('admin/system/prices')}}">
                {{ csrf_field() }}
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <label for="price_group_vendor">Price Group Vendor</label>
                                <input list="price_group_vendor" type="text" placeholder="{{request()->filled('price_group_vendor') ? request()->input('price_group_vendor') : '-- All Price Group Vendor --'}}" autocomplete="off" name="price_group_vendor" class="form-control" />
                                <datalist id="price_group_vendor">
                                    <option value="-- All Price Group Vendor --"></option>
                                    @foreach($groupVendor as $price)
                                    <option value="{{$price->price_group_vendor}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="title">Title</label>
                                <input list="title" type="text" placeholder="{{request()->filled('title') ? request()->input('title') : '-- All Title --'}}" autocomplete="off" name="title" class="form-control" />
                                <datalist id="title">
                                    <option value="-- All Title --"></option>
                                    @foreach($groupVendor as $price)
                                    <option value="{{$price->title}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="description">Description</label>
                                <input list="description" type="text" placeholder="{{request()->filled('description') ? request()->input('description') : '-- All Description --'}}" autocomplete="off" name="description" class="form-control" />
                                <datalist id="description">
                                    <option value="-- All Description --"></option>
                                    @foreach($groupVendor as $price)
                                    <option value="{{$price->description}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="status">Status</label>
                                <input list="status" type="text" placeholder="{{request()->filled('status') ? request()->input('status') : '-- All Status --'}}" autocomplete="off" name="status" class="form-control" />
                                <datalist id="status">
                                    <option value="-- All Status --"></option>
                                    @foreach($groupVendor as $price)
                                    <option value="{{$price->status}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="per_page">Per Page</label>
                                <select placeholder="Type" name="per_page" class="form-control">
                                    <option value="15" {{request()->input('per_page') == 15 ? 'selected' : ''}}>-- Per Page (15) --</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw"><i class="mdi mdi-account-search"></i>&nbsp;Search</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>


            <!-- VIEW TABLE -->
            <!-- ================================================================================================== -->

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <!-- <th>
                                Type
                            </th> -->
                        <th>
                            Price Group Vendor
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            SGD
                        </th>
                        <th>
                            USD
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
                    @foreach ($prices as $price)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            <label>{{$price->price_group_vendor}}</label>
                        </td>
                        <td>
                            {{$price->title}}
                        </td>
                        <td>
                            {{$price->description}}
                        </td>
                        <td>
                            {{$price->sgd}}
                        </td>
                        <td>
                            {{$price->usd}}
                        </td>
                        <td>
                            {{$price->status}}
                        </td>
                        <td>
                            <a href="{{url('/admin/system/prices/edit/'.$price->id )}}" class="badge badge-success text-dark">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top : 20">
                {{ $prices->appends(request()->input())->links("pagination::bootstrap-4") }}
            </div>

        </div>
    </div>
</div>

@endsection