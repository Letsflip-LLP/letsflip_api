@extends ('admin.layout',array())
@section('wrapper')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-hader">

        </div>
        <div class="card-body">

            <!-- SEARCH ENGINE -->
            <!-- ================================================================================================ -->

            <form method="GET" action="{{url('admin/user/users')}}">
                {{ csrf_field() }}
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <label for="company_id">Company</label>
                                <select required name="company_id" class="form-control">
                                    <option selected value="all">-- All Company --</option>
                                    <option value="unregistered" {{request()->input('company_id') == 'unregistered' ? 'selected' : ''}}>-- Unregistered --</option>
                                    @foreach ($companies as $com)
                                    <option {{request()->input('company_id') == $com->id ? 'selected' : ''}} value={{$com->id}}>{{$com->title}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <label for="isAdmin">Is Admin</label>
                                <select required name="isAdmin" class="form-control">
                                    <option selected value="all">-- All --</option>
                                    <option value="1" {{request()->input('isAdmin') == '1' ? 'selected' : ''}}>Admin</option>
                                    <option value="0" {{request()->input('isAdmin') == '0' ? 'selected' : ''}}>Not Admin</option>
                                </select>
                            </td>
                            <td>
                                <label for="name">Name</label>
                                <input list="name" type="text" placeholder="{{request()->filled('name') ? request()->input('name') : '-- All Name --'}}" autocomplete="off" name="name" class="form-control" />
                                <datalist id="name">
                                    <option value="-- All Name --"></option>
                                    @foreach($allUsers as $usersName)
                                    <option value="{{$usersName->first_name}} {{$usersName->last_name}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="email">Email</label>
                                <input list="email" type="text" placeholder="{{request()->filled('email') ? request()->input('email') : '-- All Email --'}}" autocomplete="off" name="email" class="form-control" />
                                <datalist id="email">
                                    <option value="-- All Email --"></option>
                                    @foreach($allUsers as $emails)
                                    <option value="{{$emails->email}}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <tr>
                        <tbody>
                            <td>
                                <label for="username">Username</label>
                                <input list="username" type="text" placeholder="{{request()->filled('username') ? request()->input('username') : '-- All Username --'}}" autocomplete="off" name="username" class="form-control" />
                                <datalist id="username">
                                    <option value="-- All Username --"></option>
                                    @foreach($allUsers as $user)
                                    <option value={{$user->username}}></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td>
                                <label for="isVerified">Is Verified</label>
                                <select required name="isVerified" class="form-control">
                                    <option selected value="all">-- All --</option>
                                    <option value="verified" {{request()->input('isVerified') == 'verified' ? 'selected' : ''}}>Verified</option>
                                    <option value="unverified" {{request()->input('isVerified') == 'unverified' ? 'selected' : ''}}>Unverified</option>
                                </select>
                            </td>
                            <td>
                                <label for="per_page">Per Page</label>
                                <select placeholder="Type" name="per_page" class="form-control">
                                    <option value="10" {{request()->input('per_page') == 10 ? 'selected' : ''}}>-- Per Page (10) --</option>
                                    <option value="15" selected>-- Per Page (15) --</option>
                                    <option value="20" {{request()->input('per_page') == 20 ? 'selected' : ''}}>-- Per Page (20) --</option>
                                    <option value="30" {{request()->input('per_page') == 30 ? 'selected' : ''}}>-- Per Page (30) --</option>
                                    <option value="50" {{request()->input('per_page') == 50 ? 'selected' : ''}}>-- Per Page (50) --</option>
                                    <option value="100" {{request()->input('per_page') == 100 ? 'selected' : ''}}>-- Per Page (100) --</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw"><i class="mdi mdi-account-search"></i>&nbsp;Search</button>
                            </td>
                        </tbody>
                    </tr>
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
                            Company
                        </th>
                        <th>
                            Is Admin
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Username
                        </th>
                        <th>
                            Is Verified
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $j = 1;
                    @endphp
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            {{$j++}}
                        </td>
                        <td>
                        </td>
                        <td>
                            @if($user->is_admin == 1)
                            <label class="badge badge-warning text-dark">Admin</label>
                            @else
                            Not Admin
                            @endif
                        </td>
                        <td>
                            {{$user->first_name.' '.$user->last_name}}
                        </td>
                        <td>
                            {{$user->email}}
                        </td>
                        <td>
                            {{$user->username}}
                        </td>

                        <td>
                            @if($user->email_verified_at != NULL)
                            <label class="badge badge-success">Verified</label>
                            @else
                            <label class="badge badge-danger">Unverified</label>

                            @endif
                        </td>
                        <td>
                            <a href="{{url('/admin/user/users/edit/'.$user->id)}}" class="badge badge-primary">Edit</a>
                        </td>
                        <td>
                            <a href="{{url('/admin/user/users/mission/'.$user->id)}}" class="badge badge-info">Mission</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top : 20">
                {{ $users->appends(request()->input())->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</div>

@endsection