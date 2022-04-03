@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">
            <h4 class="card-title">Answers</h4>
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
                            Full Name
                        </th>
                        <th>
                            Answer
                        </th>
                        <th>
                            Index
                        </th>
                        <th>
                            Point
                        </th>
                        <th>
                            Answer Status
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
                    @foreach($missionAnswers as $mission)
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
                            {{$mission->answer}}
                        </td>
                        <td>
                            {{$mission->index}}
                        </td>
                        <td>
                            {{$mission->point}}
                        </td>
                        <td>
                            @if($mission->is_true == '1')
                            <label class="badge badge-success">Correct</label>
                            @elseif($mission->is_true == '0')
                            <label class="badge badge-danger">False</label>
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