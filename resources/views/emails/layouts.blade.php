<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{url('web/css/style.css')}}" />
    <title>Let’s Fl!p</title>
    <input type="hidden" id="user_id" value="<%= userId %>" /> 
    <style>
      *{
         font-family: Arial !important;
       }
    </style>
</head>

<table width="100%" cellpadding="12" cellspacing="0" border="0">
    <tbody>
       <tr>
          <td>
             <div style="overflow: hidden;">
                <font size="-1">
                   <u></u> 
                   <div>
                      <table width="600px" align="center" style="font-family:arial">
                         <tbody>
                            <tr>
                               <td style="text-align:center">
                                  <table width="600px" cellspacing="0" cellpadding="0" style="border:1px solid #e5e5e5">
                                     <tbody>
                                        <tr>
                                           <td>
                                              <table width="100%" cellspacing="0" cellpadding="0" style="background:#fff;padding:15px">
                                                   <tbody>
                                                      <tr>
                                                      <td style="text-align:center;padding-bottom:20px;padding-right:10;padding-left:10"> <a style=" display:inline-block;padding-bottom:10px" href="{{env('LANDING_PAGE_URL',url('/'))}}" target="_blank" ><img src="{{url('template/image/Flip_logo.png')}}" width="400" style="width:200px"></a> <span style="width:100%;height:4px;border-radius:50px;display:block;
                                                                     background: rgb(223,71,135);
                                                                     margin-top : 30px;
                                                                     background: -moz-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                                                                     background: -webkit-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                                                                     background: linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
                                                                     filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#df4787',endColorstr='#3d2b93',GradientType=1);
                                                      "></span> </td>
                                                      </tr> 
                                                   <tbody>
                                                </table>
                                                      @section('content')

                                                      @show
                                             <table width="100%" cellspacing="0" cellpadding="0" style="background:#fff;padding:15px">
                                                <tbody>
                                                    <tr>
                                                       <td style="padding-top:20px;padding-bottom:10px;padding-right:20px;padding-left:20px;background:#e5e5e5">
                                                          <table width="100%" style="border-spacing:0;font-family:sans-serif;color:#333333">
                                                             <tbody>
                                                                <tr>
                                                                   <td style="padding-right:5px ; padding-left:5px">
                                                                     <p style="
                                                                           width: 100%;
                                                                           font-size: 40px;
                                                                           text-align: center;
                                                                           font-weight: 900;
                                                                           color : #333333;,
                                                                           padding-top : 10px;
                                                                     ">
                                                                        Walls down, borders<br/>
                                                                        bridged, rules torn,<br/>
                                                                        learning unleashed! 
                                                                     </p>
                                                                     <div style="text-align:center;padding-top:60px"> 
                                                                     <img width="190" src="{{url('template/image/app-button-download.png')}}"/>
                                                                     <img width="200" src="{{url('template/image/google-play-button.png')}}"/>
                                                                       <p style="margin:0;font-size:9px;color:#333333;text-align:center;padding-top:20px"> Copyright © 2020 Let’s Fl!p. All Rights Reserved. </p>
                                                                   </td>
                                                                </tr>
                                                             </tbody>
                                                          </table>
                                                       </td>
                                                    </tr>
                                                 </tbody>
                                              </table>
                                           </td>
                                        </tr>
                                     </tbody>
                                  </table>
                               </td>
                            </tr>
                         </tbody>
                      </table>
                   </div>
                </font>
             </div>
          </td>
       </tr>
    </tbody>
</table>