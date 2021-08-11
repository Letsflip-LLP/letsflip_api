@extends ('emails.layouts',array())
@section('content')
<!DOCTYPE html>

<h2>Congratulation page</h2>
<table width="600px" align="center" style="font-family: arial ; padding-right: 10px">
    <tbody>
        <tr>
            <td>
                <a
                    href=""
                    style="
                        display: block;
                        height: 240px;
                        background-position: center;
                        position: relative;
                        background-size: cover;
                        background-image: url(https://via.placeholder.com/720x280);
                    "
                >
                    <!-- <img src="image/pic2.png" alt=""> -->
                    <p
                        style="
                            text-align: left;
                            font-size: 40px;
                            color: #fff;
                            text-decoration: none;
                            padding-left: 15px;
                            font-weight: bold;
                            margin: 0;
                            position: absolute;
                            bottom: 15px;
                        "
                    >
                        <span
                            style="
                                display: block;
                            "
                            >Content</span
                        >
                    </p>
                </a>
            </td>
        </tr>
        {{-- <tr>
            <td>
                <h4
                    style="
                        font-size: 25px;
                        font-weight: bold;
                        padding-top: 10px;
                        padding-bottom: 10px;
                        margin: 0;
                        text-align: left;
                        padding-left: 10px;
                    "
                >
                    Turn the word into your classroom!
                </h4>
            </td>
        </tr>
        <tr>
            <td style="padding:10px">
                <!-- <span style="width: 30px;height:30px;border-radius:50%;background-color: #274bc7;    display: flex;                                                     align-items: center;                                                     justify-content: center;                                                     color: #fff;">1</span>                                                     <p style="font-size: 17px;font-weight:normal;padding-top: 0px;padding-bottom: 20px;flex:1;text-align:left;padding-left:10px;margin: 0;">                                                      Learn fron anywhere and anything by challenging your friends from around the world.                                                                                                              </p> -->
                <table
                    width="100%"
                    cellspacing="0"
                    cellpadding="0"
                    >
                    <tr>
                        <td
                        style="
                        padding-bottom: 20px;
                        "
                        >
                        <table
                            width="100%"
                            cellspacing="0"
                            cellpadding="0"
                            style="
                            padding-left: 10px;
                            "
                            >
                            <tr>
                                <td
                                    style="
                                    width: 30px;
                                    height: 30px;
                                    border-radius: 50%;
                                    background-color: #274bc7;
                                    text-align: center;
                                    color: #fff;
                                    vertical-align: middle;
                                    display: inline-block;
                                    line-height: 30px;
                                    "
                                    >
                                    1
                                </td>
                                <td
                                    style="
                                    font-size: 14px;
                                    color: #333;
                                    font-weight: normal;
                                    padding-top: 0px;
                                    text-align: left;
                                    padding-left: 10px;
                                    margin: 0;
                                    "
                                    >
                                    Start by tapping the "+" to create your <strong>First Mission</strong>
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                        style="
                        padding-bottom: 40px;
                        "
                        >
                        <center>
                            <img
                                src="{{url('template/image/pic1.png')}}"
                                alt=""
                                />
                        </center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <div style="background-color : #EAEAEA; padding : 10px; border-radius : 3px; margin-bottom : 40px">
                            <h4
                                style="
                                font-size: 20px;
                                font-weight: bold;
                                padding-top: 10px;
                                padding-bottom: 10px;
                                margin: 0;
                                text-align: left;
                                padding-left: 10px;
                                "
                                > WHAT IS MISSION ? </h4>
                            <p style="padding-left: 10px; font-size : 14px">Do you have a lot of Whys and Hows ? Have friends around the world to respond by creating a video mission of 60s :D
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                        style="
                        padding-bottom: 20px;
                        "
                        >
                        <table
                            width="100%"
                            cellspacing="0"
                            cellpadding="0"
                            style="
                            padding-left: 10px;
                            "
                            >
                            <tr>
                                <td
                                    style="
                                    width: 30px;
                                    height: 30px;
                                    border-radius: 50%;
                                    background-color: #ea7f64;
                                    text-align: center;
                                    color: #fff;
                                    vertical-align: middle;
                                    display: inline-block;
                                    line-height: 30px;
                                    "
                                    >
                                    2
                                </td>
                                <td
                                    style="
                                    font-size: 14px;
                                    color: #333;
                                    font-weight: normal;
                                    padding-top: 0px;
                                    text-align: left;
                                    padding-left: 10px;
                                    margin: 0;
                                    "
                                    >
                                    Video shoot any action, ask a question and tag your friends...
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                        style="
                        padding-bottom: 40px;
                        "
                        >
                        <center>
                        <img
                            src="{{url('template/image/pic2.jpg')}}"
                            alt=""
                            />
                        <center>
                        </td>
                    </tr>
                    <tr>
                        <td
                        style="
                        padding-bottom: 20px;
                        "
                        >
                        <table
                            width="100%"
                            cellspacing="0"
                            cellpadding="0"
                            style="
                            text-align: left;
                            padding-left: 10px;
                            "
                            >
                            <tr>
                                <td
                                    style="
                                    width: 30px;
                                    height: 30px;
                                    border-radius: 50%;
                                    background-color: #a94793;
                                    text-align: center;
                                    color: #fff;
                                    vertical-align: middle;
                                    display: inline-block;
                                    line-height: 30px;
                                    "
                                    >
                                    3
                                </td>
                                <td
                                    style="
                                    font-size: 14px;
                                    color: #333;
                                    font-weight: normal;
                                    padding-top: 0px;
                                    text-align: left;
                                    padding-left: 10px;
                                    margin: 0;
                                    display: inline-block;
                                    "
                                    >
                                    Your video mission will be displayed for anyone to respone (Let's Fl!p)
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="
                            padding-bottom: 20px;
                            "
                            >
                            <center>
                            <img
                                src="{{url('template/image/pic3.jpg')}}"
                                alt=""
                                />
                            </center>
                        </td>
                    </tr>
                    <!-- <tr>
                        <tr>
                            <td
                                style="
                                    padding-bottom: 20px;
                                "
                            >
                                <table
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="
                                        padding-left: 10px;
                                    "
                                >
                                    <tr>
                                        <td
                                            style="
                                                width: 30px;
                                                height: 30px;
                                                border-radius: 50%;
                                                background-color: #a94793;
                                                text-align: center;
                                                color: #fff;
                                                vertical-align: middle;
                                                display: inline-block;
                                                line-height: 30px;
                                            "
                                        >
                                            4
                                        </td>
                                        <td
                                            style="
                                                font-size: 14px;
                                                color: #333;
                                                font-weight: normal;
                                                padding-top: 0px;
                                                text-align: left;
                                                padding-left: 10px;
                                                margin: 0;
                                            "
                                        >
                                            Tag
                                            the
                                            classroom
                                            your
                                            missions
                                            should
                                            be
                                            in
                                            and
                                            tag
                                            your
                                            friends
                                            to
                                            challenge
                                            them!
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="
                                    padding-bottom: 20px;
                                "
                            >
                                <table
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="
                                        padding-left: 10px;
                                    "
                                >
                                    <tr>
                                        <td
                                            style="
                                                width: 30px;
                                                height: 30px;
                                                border-radius: 50%;
                                                background-color: #a94793;
                                                text-align: center;
                                                color: #fff;
                                                vertical-align: middle;
                                                display: inline-block;
                                                line-height: 30px;
                                            "
                                        >
                                            5
                                        </td>
                                        <td
                                            style="
                                                font-size: 14px;
                                                color: #333;
                                                font-weight: normal;
                                                padding-top: 0px;
                                                flex: 1;
                                                text-align: left;
                                                padding-left: 10px;
                                                margin: 0;
                                            "
                                        >
                                            Mission
                                            will
                                            be
                                            uploaded
                                            onto
                                            the
                                            dashboard
                                            for
                                            anyone
                                            to
                                            view
                                            and
                                            respond
                                            to.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr> -->
                </table>
            </td>
        </tr>
        <tr>
            <td
                style="
                    text-align: center;
                    padding: 5px;
                    padding-top: 25px;
                    padding-bottom: 20px;
                "
            >
                <a
                    href=""
                    style="
                        width: 50%;
                        display: block;
                        margin: 0 auto;
                        height: 50px;
                        background-size: contain;
                        background-repeat: no-repeat;
                        background: rgba(
                            61,
                            40,
                            147,
                            1
                        );
                        background: -moz-linear-gradient(
                            left,
                            rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                                0%,
                            rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                                100%
                        );
                        background: -webkit-gradient(
                            left top,
                            right top,
                            color-stop(
                                0%,
                                rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                            ),
                            color-stop(
                                100%,
                                rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                            )
                        );
                        background: -webkit-linear-gradient(
                            left,
                            rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                                0%,
                            rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                                100%
                        );
                        background: -o-linear-gradient(
                            left,
                            rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                                0%,
                            rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                                100%
                        );
                        background: -ms-linear-gradient(
                            left,
                            rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                                0%,
                            rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                                100%
                        );
                        background: linear-gradient(
                            to right,
                            rgba(
                                    61,
                                    40,
                                    147,
                                    1
                                )
                                0%,
                            rgba(
                                    223,
                                    71,
                                    134,
                                    1
                                )
                                100%
                        );
                        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3d2893', endColorstr='#df4786', GradientType=1 );
                        border-radius: 10px;
                        color: #fff;
                        text-decoration: none;
                        line-height: 50px;
                    "
                    >Create Your Mission
                    Now</a
                >
            </td>
        </tr> --}}
    </tbody>
</table>

@stop
