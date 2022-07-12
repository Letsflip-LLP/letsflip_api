@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">

            <!-- SEARCH ENGINE -->
            <!-- ================================================================================================ -->


            <!-- VIEW TABLE -->
            <!-- ================================================================================================== -->

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Rapporteur
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Reported at
                        </th>
                    </tr>
                </thead>
                @php
                $j = 1;
                @endphp
                <tbody>
                    @foreach($details as $d)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                            @if($d->UserFrom)
                            {{$d->UserFrom->first_name}}&nbsp;{{$d->UserFrom->last_name}}
                            @else
                            <p style="color: red;"><b>USER NOT FOUND</b></p>
                            @endif
                        </td>
                        <td>
                            {{$d->title}}
                        </td>
                        <td>
                            {{$d->text}}
                        </td>
                        <td>
                            {{$d->updated_at}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top : 20">

            </div>
        </div>
    </div>
</div>

@endsection