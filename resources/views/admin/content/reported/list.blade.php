@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">

            <!-- SEARCH ENGINE -->
            <!-- ================================================================================================ -->
            <form method="GET" action="{{url('admin/reported/content')}}">
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
                            Content Owner
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Content
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
                            @if($rp->Classroom)
                            {{$rp->Classroom->User->first_name}}&nbsp;{{$rp->Classroom->User->last_name}}
                            @elseif($rp->Mission)
                            {{$rp->Mission->User->first_name}}&nbsp;{{$rp->Mission->User->last_name}}
                            @elseif($rp->Response)
                            {{$rp->Response->User->first_name}}&nbsp;{{$rp->Response->User->last_name}}
                            @elseif($rp->MissionComment)
                            {{$rp->MissionComment->User->first_name}}&nbsp;{{$rp->MissionComment->User->last_name}}
                            @endif
                        </td>
                        <td>
                            @if($rp->Classroom)
                            Classroom
                            @elseif($rp->Mission)
                            Mission
                            @elseif($rp->Response)
                            Response
                            @elseif($rp->MissionComment)
                            Comment
                            @endif
                        </td>
                        <td>
                            @if($rp->Classroom)
                            {{$rp->Classroom->title}}
                            @elseif($rp->Mission)
                            {{$rp->Mission->title}}
                            @elseif($rp->Response)
                            {{$rp->Response->title}}
                            @endif
                        </td>
                        <td>
                            @if($rp->Classroom)
                            {{$rp->Classroom->text}}
                            @elseif($rp->Mission)
                            {{$rp->Mission->text}}
                            @elseif($rp->Response)
                            {{$rp->Response->text}}
                            @elseif($rp->MissionComment)
                            {{$rp->MissionComment->text}}
                            @endif
                        </td>
                        <td>
                            @if($rp->Classroom)

                            @elseif($rp->Mission)
                            <a href="{{url('admin/reported/open-content/'.$rp->Mission->id)}}" target="_blank" class="badge badge-info">View</a>
                            @elseif($rp->Response)
                            <a href="{{url('admin/reported/open-content/'.$rp->Response->id)}}" target="_blank" class="badge badge-info">View</a>
                            @endif
                        </td>
                        <td>
                            {{$rp->max('updated_at')}}
                        </td>
                        <td>
                            Inappropriate:
                            @if($rp->Classroom)
                            {{$rp->where([['classroom_id', $rp->classroom_id],['title', "It's inappropriate"]])->count()}}
                            @elseif($rp->Mission)
                            {{$rp->where([['mission_id', $rp->mission_id],['title', "It's inappropriate"]])->count()}}
                            @elseif($rp->Response)
                            {{$rp->where([['mission_respone_id', $rp->mission_respone_id],['title', "It's inappropriate"]])->count()}}
                            @elseif($rp->MissionComment)
                            {{$rp->where([['mission_comment_id', $rp->mission_comment_id],['title', "It's inappropriate"]])->count()}}
                            @endif <br>
                            Spam:
                            @if($rp->Classroom)
                            {{$rp->where([['classroom_id', $rp->classroom_id],['title', "It's spam"]])->count()}}
                            @elseif($rp->Mission)
                            {{$rp->where([['mission_id', $rp->mission_id],['title', "It's spam"]])->count()}}
                            @elseif($rp->Response)
                            {{$rp->where([['mission_respone_id', $rp->mission_respone_id],['title', "It's spam"]])->count()}}
                            @elseif($rp->MissionComment)
                            {{$rp->where([['mission_comment_id', $rp->mission_comment_id],['title', "It's spam"]])->count()}}
                            @endif
                            <br> <br>
                            @if($rp->Classroom)
                            <a href="{{url('admin/reported/details/'.$rp->Classroom->id)}}" class="badge badge-info">Details</a>
                            @elseif($rp->Mission)
                            <a href="{{url('admin/reported/details/'.$rp->Mission->id)}}" class="badge badge-info">Details</a>
                            @elseif($rp->Response)
                            <a href="{{url('admin/reported/details/'.$rp->Response->id)}}" class="badge badge-info">Details</a>
                            @elseif($rp->MissionComment)
                            <a href="{{url('admin/reported/details/'.$rp->MissionComment->id)}}" class="badge badge-info">Details</a>
                            @endif
                        </td>
                        <td>
                            @if($rp->Classroom)
                            <button type="submit" onclick="deleteConfirmation('{{$rp->Classroom->id}}')" class="btn btn-danger">Take Down</button>
                            @elseif($rp->Mission)
                            <button type="submit" onclick="deleteConfirmation('{{$rp->Mission->id}}')" class="btn btn-danger">Take Down</button>
                            @elseif($rp->Response)
                            <button type="submit" onclick="deleteConfirmation('{{$rp->Response->id}}')" class="btn btn-danger">Take Down</button>
                            @elseif($rp->MissionComment)
                            <button type="submit" onclick="deleteConfirmation('{{$rp->MissionComment->id}}')" class="btn btn-danger">Take Down</button>
                            @endif
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

<script>
    function deleteConfirmation(id) {
        let confirmation = prompt("PLEASE ENTER 't@k3_d0wn_c0nt3nt': ");
        if (confirmation == "")
            alert("Please enter the keywords");
        else if (confirmation == null)
            return;
        else if (confirmation == "t@k3_d0wn_c0nt3nt") {
            let crossCheck = confirm("This content will be taken down. Proceed? (OK/Cancel)");
            if (crossCheck) {
                location.href = "/admin/reported/take-down/"+id;
                alert("Content has been taken down");
            } else {
                location.href = "";
                alert("Canceled");
            }
        } else
            alert("Wrong Input !!");
    }
</script>

@endsection