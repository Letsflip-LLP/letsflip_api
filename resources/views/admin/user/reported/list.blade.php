@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">

            <!-- SEARCH ENGINE -->
            <!-- ================================================================================================ -->
            <form method="GET" action="{{url('admin/reported')}}">
                {{ csrf_field() }}
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <!-- <td>
                                <label for="name">Content Owner</label>
                                <input type="text" placeholder="" autocomplete="off" name="name" class="form-control" />
                            </td> -->
                            <td>
                                <label for="type">Type</label>
                                <select required name="type" class="form-control">
                                    <option selected value="all">-- All --</option>
                                    <option value="Mission" {{request()->input('type') == 'Mission' ? 'selected' : ''}}>Mission</option>
                                    <!-- <option value="">Comment</option> -->
                                    <option value="Response" {{request()->input('type') == 'Response' ? 'selected' : ''}}>Response</option>
                                    <option value="Classroom" {{request()->input('type') == 'Classroom' ? 'selected' : ''}}>Classroom</option>
                                    <option value="MissionComment" {{request()->input('type') == 'MissionComment' ? 'selected' : ''}}>Comment</option>
                                </select>
                            </td>
                            <!-- <td>
                                <label for="title">Title</label>
                                <input list="title" type="text" placeholder="" autocomplete="off" name="title" class="form-control" />
                            </td>
                            <td>
                                <label for="description">Description</label>
                                <input list="description" type="text" placeholder="" autocomplete="off" name="description" class="form-control" />
                            </td> -->
                            <!-- <td>
                                <label for="order">Order By</label>
                                <select required name="order" class="form-control">
                                    <option selected value="all">-- All --</option>
                                    <option value="reported">Most Reported</option>
                                    <option value="spam">Most Spam</option>
                                    <option value="inappropriate">Most Inappropriate</option>
                                </select>
                            </td> -->
                            <td>
                                <label for="per_page">Per Page</label>
                                <select placeholder="Type" name="per_page" class="form-control">
                                    <option value="10" selected>-- Per Page (10) --</option>
                                    <option value="20" {{request()->input('per_page') == 20 ? 'selected' : ''}}>-- Per Page (20) --</option>
                                    <option value="30" {{request()->input('per_page') == 30 ? 'selected' : ''}}>-- Per Page (30) --</option>
                                    <option value="50" {{request()->input('per_page') == 50 ? 'selected' : ''}}>-- Per Page (50) --</option>
                                    <option value="100" {{request()->input('per_page') == 100 ? 'selected' : ''}}>-- Per Page (100) --</option>
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
                        <th>
                            Full Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Mission
                        </th>
                        <th>
                            Classroom
                        </th>
                        <th>
                            Follower
                        </th>
                        <th>
                            Following
                        </th>
                        <th>
                            Total Points
                        </th>
                        <th>
                            Last Reported
                        </th>
                        <th>
                            Reported Details
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $j = 1;
                    @endphp
                    @foreach($report as $rp)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            {{$rp->UserTo->first_name}}&nbsp;{{$rp->UserTo->last_name}}
                        </td>
                        <td>
                            {{$rp->UserTo->email}}
                        </td>
                        <td>
                            @if($rp->UserTo->Subscribe)
                            <label class="{{$rp->UserTo->Subscribe->type == 2 ? 'badge badge-success' : ''}} {{$rp->UserTo->Subscribe->type == 3 ? 'badge badge-warning text-dark' : ''}} {{$rp->UserTo->Subscribe->type == 1 ? 'badge badge-secondary' : ''}}">{{subsType($rp->UserTo->Subscribe->type)->name}}</label>
                            @else
                            Basic
                            @endif
                        </td>
                        <td>
                            {{$rp->UserTo->Mission->count()}}
                        </td>
                        <td>
                            {{$rp->UserTo->ClassRoom->count()}}
                        </td>
                        <td>
                            {{$rp->UserTo->Follower->count()}}
                        </td>
                        <td>
                            {{$rp->UserTo->Followed->count()}}
                        </td>
                        <td>
                            {{$rp->UserTo->Point->sum('value')}}
                        </td>
                        <td>
                            {{$rp->updated_at}}
                        </td>
                        <td>
                            Inappropriate:
                            {{$rp->where([['user_id_to', $rp->user_id_to],['title', "It's inappropriate"]])->count()}}<br>
                            Spam:
                            {{$rp->where([['user_id_to', $rp->user_id_to],['title', "It's spam"]])->count()}}
                            <br> <br>
                            <a href="{{url('admin/reported/user/details/'.$rp->user_id_to)}}" class="badge badge-info">Details</a>
                        </td>
                        <td>
                            <!-- <a href="{{url('admin/reported/user/block/'.$rp->user_id_to)}}" class="btn btn-danger">Block</a> -->
                            <!-- <a href="" class="btn btn-danger">Block</a> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $report->appends(request()->input())->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</div>


@endsection