@extends ('emails.layouts',array())
@section('content')
    <table width="100%">
        <tbody>
        <tr>
        <td style="font-size:14px;color:#333;font-weight:bold;padding-top:10px;padding-bottom:50px;text-align:left;padding-left:10px">  Hey there!  </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> Thank you for being a Let’s Fl!p warrior and supporting our app. We hope your user experience has been a pleasant one so far. We are inviting you to upgrade your account to enjoy more exclusive features With the upgrade you will enjoy access to {{$account_type}} & Missions that is accessible only via special Classroom Keys</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Your invite link is:&nbsp;</span><a href="{{$url}}">Click Here</a></td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> Please click on the invite link to upgrade your account. No further action is required. If your account has been updated, please logout and log in again.</td>
        </tr>
        <tr>
        </tr>
        </tbody>
    </table>
@endsection
