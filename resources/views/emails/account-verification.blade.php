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
                                                    <td style="text-align:center;padding-bottom:20px"> <a style="display:inline-block;padding-bottom:10px" href="{{env('LANDING_PAGE_URL',url('/'))}}" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=id&amp;q=https://u9758790.ct.sendgrid.net/ls/click?upn%3Dd3aH0s504W-2BuA0paWNZI0J5CKbfkPmuf2PV8Q4CH1-2FmYRmLra-2BUUWqOdJcd-2FWhBTXlWF_hk6glN-2BwelaTB5OwdOF56JlD2mZPv3KXvabU8GPntDrdwXafo9a4VmPwmZcz03opPkOl-2F1t6q6-2Bd2Gb92qmVv6oGKAk3O9tgzbs-2FZvfLf24ibVvEldEa-2FMWybVuckwsitZsc-2FgsXy9xQXq22PUWP0AG8KmL-2FXMWRAB3apfBcFGqUcBlMPaLMxxREpKBBtLgK5dxh3L87Ujb3BaACB4X6c9AP7hGu2ioBgRohm42Ls1I-3D&amp;source=gmail&amp;ust=1606832284846000&amp;usg=AFQjCNEXSGwtFKor86u5dl6Hn8QuQCKdfw"><img src="https://ci6.googleusercontent.com/proxy/73SN7alATfD4QNaDmrRHcT2o3ZyZ60HQNWSpCj7Dy69hI71wT8sbkOwtCNm1RKGQqUgOdyEky3zuNmOQ4tWlABZAsFygePSSjiU=s0-d-e1-ft#http://35.240.213.126:3000/template/image/Flip_logo.png" width="400" style="width:200px"></a> <span style="width:100%;height:4px;border-radius:50px;display:block;background:rgba(61,40,147,1);background:-moz-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(204,72,142,1) 100%);background:-webkit-gradient(left top,right top,color-stop(0%,rgba(61,40,147,1)),color-stop(100%,rgba(204,72,142,1)));background:-webkit-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(204,72,142,1) 100%);background:-o-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(204,72,142,1) 100%);background:-ms-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(204,72,142,1) 100%);background:linear-gradient(to right,rgba(61,40,147,1) 0%,rgba(204,72,142,1) 100%)"></span> </td>
                                                    </tr>
                                                    <tr>
                                                       <td style="font-size:14px;color:#333;font-weight:bold;padding-top:10px;padding-bottom:50px;text-align:left;padding-left:10px"> Hello Andhi Saputro, </td>
                                                    </tr>
                                                    <tr>
                                                       <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> Congratulations on creation of your account. </td>
                                                    </tr>
                                                    <tr>
                                                       <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> Here are your login details: </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Email Address:</span> <a href="mailto:andhi.saputro1508@gmail.com" target="_blank">{{$email}}</a> </td>
                                                    </tr>
                                                    <tr>
                                                       <td style="font-size:14px;color:#333;font-weight:normal;padding-top:0px;padding-bottom:20px;text-align:left;padding-left:10px"> <span style="font-weight:bold;display:inline-block">Password:</span> {{$password}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;padding:5px;padding-top:25px;padding-bottom:20px"> <a href="{{$activate_url}}" style="width:200px;display:block;margin:0 auto;height:50px;background-size:contain;background-repeat:no-repeat;background:rgba(61,40,147,1);background:-moz-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(223,71,134,1) 100%);background:-webkit-gradient(left top,right top,color-stop(0%,rgba(61,40,147,1)),color-stop(100%,rgba(223,71,134,1)));background:-webkit-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(223,71,134,1) 100%);background:-o-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(223,71,134,1) 100%);background:-ms-linear-gradient(left,rgba(61,40,147,1) 0%,rgba(223,71,134,1) 100%);background:linear-gradient(to right,rgba(61,40,147,1) 0%,rgba(223,71,134,1) 100%);border-radius:10px;color:#fff;text-decoration:none;line-height:50px" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=id&amp;q=https://u9758790.ct.sendgrid.net/ls/click?upn%3DHGtNpnJrPV84w3aLQRNfohvLzuNSKVAoFThN3pdyo-2BtHJB7iC5LqAMps0Q5mWq7LYFL9M9mRtBRJBVsB0O2eovvtD9vkWsq2RlLi4nOJ9GBpZKGHSJosX-2FAEmK0XzoxWJ7te_hk6glN-2BwelaTB5OwdOF56JlD2mZPv3KXvabU8GPntDrdwXafo9a4VmPwmZcz03opPkOl-2F1t6q6-2Bd2Gb92qmVv-2BGEgsL4G-2BlOEXidDnjT4El6siwRdGVDH39TPFodFj42BmYKhLOSG-2F5JfPezu7Zhley5ud6beQmOcJNyQSwBoLcBGqp4riEUs9Te7gaaBi088q20iC9SrMGLM5KntP8ZTeELGEe2Ggaj9GT0-2BrXFNlI-3D&amp;source=gmail&amp;ust=1606832284846000&amp;usg=AFQjCNEIxzT6wUWyBP83m0rNLFREJEQgdQ">Activate your account</a> </td>
                                                    </tr>
                                                    <tr>
                                                       <td style="padding-top:20px;padding-bottom:10px;padding-right:20px;padding-left:20px;background:#e5e5e5">
                                                          <table width="100%" style="border-spacing:0;font-family:sans-serif;color:#333333">
                                                             <tbody>
                                                                <tr>
                                                                   <td style="text-align:center;padding-bottom:15px">
                                                                      <p style="font-size:30px;font-weight:bold;text-align:center;margin-bottom:0;margin:0;padding-top:10px"> <span style="display:block">Walls down, borders</span> bridged, rules torn, <span style="display:block">learning unleashed!</span> </p>
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