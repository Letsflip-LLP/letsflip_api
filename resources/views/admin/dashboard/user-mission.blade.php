@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">
            <h4 class="card-title">Missions</h4>
            {{-- <p class="card-description"> Basic form layout </p> --}}
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Question(s)
                        </th>
                        <th>
                            Comment(s)
                        </th>
                        <th>
                            Response(s)
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
                    @endphp
                    @foreach($missions as $mission)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            {{$mission->title}}
                        </td>
                        <td>
                            {{$mission->text}}
                        </td>
                        <td>
                            @if($mission->status == '1')
                            <label>Publish</label>
                            @elseif($mission->status == '2')
                            <label class="badge badge-primary">Draft</label>
                            @elseif($mission->status == '3')
                            <label class="badge badge-primary">Archived</label>
                            @endif
                        </td>
                        <td>
                            @if($mission->type == '1')
                            <label class="badge badge-danger">Public</label>
                            @elseif($mission->type == '2')
                            <label class="badge badge-success">Private</label>
                            @elseif($mission->type == '3')
                            <label class="badge badge-warning">Master</label>
                            @endif
                        </td>
                        <td>
                            @if($missionQuestionExist->where('mission_id', $mission->id)->first() !== null)
                            <a href="{{url('/admin/user/users/mission/questions/'.$mission->id)}}" class="badge badge-info">See Questions and Answers</a>
                            @else
                            No Question(s)
                            @endif
                        </td>
                        <td>
                            @if($missionCommentExist->where('mission_id', $mission->id)->first() !== null)
                            <a href="{{url('/admin/user/users/mission/comments/'.$mission->id)}}" class="badge badge-info">See Comments</a>
                            @else
                            0 Comment
                            @endif
                        </td>
                        <td>
                            @if($missionResponseExist->where('mission_id', $mission->id)->first() !== null)
                            <a href="{{url('/admin/user/users/mission/responses/'.$mission->id)}}" class="badge badge-info">See Responses</a>
                            @else
                            0 Response
                            @endif
                        </td>
                        <td>
                            {{$mission->created_at}}
                        </td>
                        <td>
                            {{$mission->updated_at}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <a class="btn btn-light" href="{{url('admin/user/users')}}">Back to All Users</a>
        </div>
    </div>
</div>

@endsection