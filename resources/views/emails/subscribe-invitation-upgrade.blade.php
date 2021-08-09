@extends ('emails.layouts',array())
@section('content')
    <table width="100%">
        <tbody>
        <tr>
        <td style="font-size:14px;color:#333;font-weight:bold;padding-top:10px;padding-bottom:50px;text-align:left;padding-left:10px">  Hello learning community.  </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px">
                I see the awesome work you are putting into making this world of learning a better space and we want to invite you to do more. You are receiving this email because you have been upgraded to enjoy the {{$account_type}} space. You now have access to creating your own,
            </td>
        </tr>
        <tr>
            <td>
                <ol>
                    <li>{{$account_type}}</li>
                    <li>Learning Journeys</li>
                    <li>Grading</li>
                    <li>Grading Reports</li>
                    <li>Leaderboards</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px">
                <strong>Collaboration Vs Competition</strong> through <strong>Authentic Learning </strong> is now a bigger and better experience. Do invite your fellow learners to download Let's FL!P and be a part of this exciting new experience. You have been upgraded and the only thing you need to do right now is open the app up and start building learning communities.
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px">
                To the learners of the world,
            </td>
        </tr>
        <tr>
            <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px">
                Team Let's FL!P
            </td>
        </tr>
        <tr>
        </tr>
        </tbody>
    </table>
@endsection
