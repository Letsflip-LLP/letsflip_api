@extends ('emails.layouts',array())
@section('content')
    <table width="100%">
        <tbody>
        <tr>
        <td style="font-size:14px;color:#333;font-weight:bold;padding-top:10px;padding-bottom:50px;text-align:left;padding-left:10px">  Hey there!  </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> You have been invited to join As a member of the Let’s Fl!p community, you will enjoy access to a variety of {{$account_type}} and missions created by educators and students from all over the world.</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Your invite link is:&nbsp;</span><a href="{{$url}}">Click Here</a></td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Email Address:&nbsp;</span>{{$email}}</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Account Type:&nbsp;</span>{{$account_type}}</td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> Please click on the invite link to begin your Let’s Fl!p journey with us! Have fun and we wish you all the best.</td>
        </tr>
        <tr>
        </tr>
        </tbody>
    </table>
@endsection
