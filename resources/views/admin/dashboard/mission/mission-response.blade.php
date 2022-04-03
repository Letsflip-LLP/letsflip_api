@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">
            <h4 class="card-title">Responses</h4>
            {{-- <p class="card-description"> Basic form layout </p> --}}
            <br>
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
                            Status
                        </th>
                        <th>
                            Image
                        </th>
                        <th>
                            Image / Video
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Comments
                        </th>
                        <th>
                            Created at
                        </th>
                        <th>
                            Updated at
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $j = 1;
                    $count = 0;
                    @endphp
                    @foreach($missionResponses as $mission)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            {{$user[$count]->email}}
                        </td>
                        <td>
                            {{$user[$count]->first_name}} {{$user[$count]->last_name}}
                        </td>
                        <td>
                            @if($mission->type == '1')
                            Public Response
                            @elseif($mission->type == '2')
                            Private Response
                            @endif
                        </td>
                        <td>
                            @if($mission->status == '1')
                            Publish
                            @elseif($mission->status == '2')
                            Draft
                            @elseif($mission->status == '3')
                            Archived
                            @endif
                        </td>
                        <td>
                            Not Ready Yet
                        </td>
                        <td>
                            Not Ready Yet
                        </td>
                        <td>
                            {{$mission->text}}
                        </td>
                        <td>
                        @if($missionResponseCommentExist->where('mission_respone_id', $mission->id)->first() !== null)
                            <a href="{{url('/admin/user/users/mission/responsecomments/'.$mission->id)}}" class="badge badge-info">See Comments</a>
                            @else
                            0 Comment
                            @endif
                        </td>
                        <td>
                            {{$mission->created_at}}
                        </td>
                        <td>
                            {{$mission->updated_at}}
                        </td>
                    </tr>
                    <?php ++$count; ?>
                    @endforeach
                </tbody>
            </table>
            <br>
            <a class="btn btn-light" href="{{url('admin/user/users')}}">Back to All Users</a>
        </div>
    </div>
</div>

@endsection