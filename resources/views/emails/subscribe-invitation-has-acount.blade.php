@extends ('emails.layouts',array())
@section('content')
    <table width="100%">
        <tbody>
        <tr>
        <td style="font-size:14px;color:#333;font-weight:bold;padding-top:10px;padding-bottom:50px;text-align:left;padding-left:10px">  Hey there!  </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> We are invited you to upgrade your account.</td>
        </tr> 
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Email Address:&nbsp;</span>{{$email}}</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Account Type:&nbsp;</span>{{$account_type}}</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">
                There is no required to any action, if your account has not been updated, please logout and relogin.
            </td>
        </tr>
        <tr>
        </tr>
        {{-- <tr>
            <td style="text-align:center;"> <a href="{{url('/subscription/accept-invitation')}}" style="
                color: #fff;
                width: 90%;
                display: inline-block;
                padding-top: 25px;
                padding-bottom: 25px;
                margin-bottom: 20px;
                border-radius: 13px;   
                background: rgb(223,71,135);
                margin-top : 30px;
                background: -moz-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                background: -webkit-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                background: linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#df4787',endColorstr='#3d2b93',GradientType=1);
                " target="_blank">Create Your Account</a></td>
        </tr> --}}
        </tbody>
    </table>
@endsection