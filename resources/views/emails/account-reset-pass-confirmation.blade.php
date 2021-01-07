@extends ('emails.layouts',array())
@section('content')

<table>
	<tbody>
		<tr>
		<td
			style="
				font-size: 14px;
				padding-top: 6px;
				padding-bottom: 15px;
				text-align: left;
				padding-left: 10px;
				color: #333;
			"
		>
			Hello {{$first_name}}&nbsp;{{$last_name}}
		</td>
	</tr>
	<tr>
		<td
			style="
				font-size: 14px;
				color: #333;
				font-weight: normal;
				padding-top: 0px;
				padding-bottom: 0;
				text-align: left;
				padding-left: 10px;
			"
		>
			A request has been
			received to reset the
			password for your Let’s
			Fl!p account
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
				href="{{$reset_password_url}}"
				style="
					width: 100%;
					display: block;
					margin: 0 auto;
					height: 50px;
					background-size: contain;
					background-repeat: no-repeat;
					border-radius: 13px;   
					background: rgb(223,71,135);
					margin-top : 30px;
					background: -moz-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
					background: -webkit-linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
					background: linear-gradient(90deg, rgba(223,71,135,1) 0%, rgba(46,77,196,1) 50%, rgba(61,43,147,1) 100%);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#df4787',endColorstr='#3d2b93',GradientType=1);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3d2893', endColorstr='#df4786', GradientType=1 );
					border-radius: 10px;
					color: #fff;
					text-decoration: none;
					line-height: 50px;
				"
				>CHANGE PASSWORD</a
			>
		</td>
	</tr>
	<tr>
		<td
			style="
				font-size: 14px;
				color: #333;
				font-weight: normal;
				padding-top: 17px;
				padding-bottom: 15px;
				text-align: left;
				padding-left: 10px;
			"
		>
			If you did not initiate
			this request , please
			contact us immediately
			at
			<span
				style="
					display: inline-block;
					color: #274bc7;
				"
				>support@getletsflip.com</span
			>
		</td>
	</tr>
	<tr>
		<td
			style="
				padding-bottom: 95px;
			"
		>
			<p
				style="
					font-size: 14px;
					color: #333;
					font-weight: normal;
					padding-top: 0px;
					padding-bottom: 8px;
					text-align: left;
					padding-left: 10px;
					margin: 0;
				"
			>	<br/>
				Thank you,
			</p>
			<p
				style="
					font-size: 14px;
					color: #333;
					font-weight: normal;
					padding-top: 0px;
					padding-bottom: 20px;
					text-align: left;
					padding-left: 10px;
					margin: 0;
				"
			>
				Let’s Fl!p
				Application team
			</p>
		</td>
	</tr>
	</tbody>
</table>
@stop