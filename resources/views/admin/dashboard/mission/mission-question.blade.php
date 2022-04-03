@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">
            <h4 class="card-title">Questions</h4>
            {{-- <p class="card-description"> Basic form layout </p> --}}
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Question
                        </th>
                        <th>
                            Option 1
                        </th>
                        <th>
                            Option 2
                        </th>
                        <th>
                            Option 3
                        </th>
                        <th>
                            Option 4
                        </th>
                        <th>
                            Option 5
                        </th>
                        <th>
                            Correct Option
                        </th>
                        <th>
                            Question Type
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $j = 1;
                    @endphp
                    @foreach($missionQuestions as $mission)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            @if($mission->type == '1')
                            Quick Scores
                            @elseif($mission->type == '2')
                            Learning Journey
                            @endif
                        </td>
                        <td>
                            {{$mission->title}}
                        </td>
                        <td>
                            @if($mission->option1 != NULL)
                            {{$mission->option1}}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($mission->option2 != NULL)
                            {{$mission->option2}}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($mission->option3 != NULL)
                            {{$mission->option3}}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($mission->option4 != NULL)
                            {{$mission->option4}}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($mission->option5 != NULL)
                            {{$mission->option5}}
                            @else
                            -
                            @endif
                        </td>

                        <td>{{$mission->correct_option}}</td>
                        <td>
                            @if($mission->question_type == '1')
                            Multiple Choice
                            @else
                            Text
                            @endif
                        </td>
                        <td>
                            @if($missionAnswerExist ->where('question_id', $mission->id)->first() !== null)
                            <a href="{{url('/admin/user/users/mission/answers/'.$mission->id)}}" class="badge badge-info">See User Answer(s)</a>
                            @else
                            0 Answer
                            @endif
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