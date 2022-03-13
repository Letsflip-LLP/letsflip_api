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
                            Mission ID
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Mission Question(s)
                        </th>
                        <th>
                            Mission Answer(s)
                        </th>
                        <th>
                            Mission Comment(s)
                        </th>
                        <th>
                            Response Comment(s)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            2
                        </td>
                        <td>
                            3
                        </td>
                        <td>
                            4
                        </td>
                        <td>
                            5
                        </td>
                        <td>
                            6
                        </td>
                        <td>
                            7
                        </td>
                        <td>
                            8
                        </td>
                        <td>
                            9
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-light" href={{url('admin/user/users')}}>Back</a>
        </div>
    </div>
</div>

@endsection