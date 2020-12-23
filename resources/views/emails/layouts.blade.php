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
                                                      <td style="text-align:center;padding-bottom:20px;padding-right:10;padding-left:10"> <a style=" display:inline-block;padding-bottom:10px" href="{{env('LANDING_PAGE_URL',url('/'))}}" target="_blank" ><img src="https://ci6.googleusercontent.com/proxy/73SN7alATfD4QNaDmrRHcT2o3ZyZ60HQNWSpCj7Dy69hI71wT8sbkOwtCNm1RKGQqUgOdyEky3zuNmOQ4tWlABZAsFygePSSjiU=s0-d-e1-ft#http://35.240.213.126:3000/template/image/Flip_logo.png" width="400" style="width:200px"></a> <span style="width:100%;height:4px;border-radius:50px;display:block;
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
                                                                     <div style="text-align:center;padding-top:60px"> <a style="width:29%;background-image:url(https://ci4.googleusercontent.com/proxy/zErr9ck_MnZqrMN5fJFN45_L8YuKRGhfCMiww5a02xpEVLoJkJ_O29kofDgwGa7P7VLSAimYAJaAD35MwSUWuwJ9J02AyRI=s0-d-e1-ft#http://35.240.213.126:3000/template/image/iosBtn.png);height:50px;background-size:contain;background-repeat:no-repeat;border:0;padding:0;margin-right:15px;display:inline-block"></a> <a style="width:30%;background-image:url(https://ci6.googleusercontent.com/proxy/gEAOqlzSRGduvxYWhEiN3Lvpk-zNsYM86rEL3RsfW6VS7RL_NX159ehJudepW0IRrmlkEOQfcgHm6oJciBve9qmFMlga9wF32Ilw=s0-d-e1-ft#http://35.240.213.126:3000/template/image/androidBtn.png);height:50px;background-size:contain;background-repeat:no-repeat;border:0;padding:0;display:inline-block"></a> </div>
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
                      <img src="https://ci6.googleusercontent.com/proxy/YH7V25Qhk1AP7J-rulKZaGxi87usd3SCSfAh7dDyB9UipxozJShXUGDefGMvFQwBU90Iyo8ZqsaTmVzTerLWmP4RY9j3hhA3TsV4moWMkbk1guZWoT6Jc5GsKHl_Oi1PD-mhYcTJcfEsVN3TJtJ8Itoy24J6e-C1xR1imUnR1RJDAb0FIs7-z8DRMiTVViG1I5VhLEE5yLZ2gpHmBLuy6hNb9PTaL0fVTR0pDBqPTTs8JvvCgP3_TNsluJOCBQo8ZVj09S_WewEb4K9xk7HFyvLaT0wxGh5YjqBEhmD0ChNHlLM98Q-ofZgIOUwK2agPl-LF8oELjiJSUoEVxTU4htkqaQL8bpVVuBtZUTyLyshxzhk_O_Ewnwel9g5MRdxY1P6wZtNWMm9QWfmwjLswPF5IqpwrcNL-BdWAzxPrZNlh0u1iu_7Zxjp6EVp3Rq3H6e5iSQM=s0-d-e1-ft#https://u9758790.ct.sendgrid.net/wf/open?upn=ZuNT-2B5-2FMBtfjTNu-2BvpfRfapfK4Ierdc2p25vHOgS6NlF0jGZt-2FdrZ3fY-2BIRswYB19pT6IyNkw1UcGrmoJMYiPzrM4DUL-2FhjQW73j0Gp-2B7RR9zVbu9CkPdCqP7v5EpVvB2BSk-2FSM1yvv80kLoVOTX4iC4GvXOZpjKeYOr6YeyMOl6Jwn6DQsVrflaPBtNh-2B7e6vqzmrRnZZ576UKB5ctx2pCDs9wILx6o-2Ba52Qu74ur5gfVqTKrxZme6SuYPBINXx" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">
                   </div>
                </font>
             </div>
          </td>
       </tr>
    </tbody>
</table>