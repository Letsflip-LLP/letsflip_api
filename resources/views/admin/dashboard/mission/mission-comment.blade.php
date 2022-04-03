@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">
            <h4 class="card-title">Comments</h4>
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
                            Comment
                        </th>
                        <th>
                            Status
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
                    @foreach($missionComments as $mission)
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
                            {{$mission->text}}
                        </td>
                        <td>
                            @if($mission->status == '1')
                            Live
                            @elseif($mission->status == '0')
                            Removed by Admin
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