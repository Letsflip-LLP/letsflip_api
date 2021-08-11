<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="/web/css/style.css" type="text/css" rel="stylesheet" />
		<title>Confirm reset password</title>
	</head>
	<body>
		<header style="background:linear-gradient(0deg, rgba(255,211,56,1) 0%, rgba(223,71,135,1) 100%);">
			<div class="logo" style="background-color:linear-gradient(0deg, rgba(255,211,56,1) 0%, rgba(223,71,135,1) 100%);">
				<img src="/web/images/logo.png" alt="" />
			</div>
		</header>
		<div class="page-container">
			<div class="left-side">
				<p>
					{{$message}}
				</p>
				<h3 style="color:linear-gradient(0deg, rgba(255,211,56,1) 0%, rgba(223,71,135,1) 100%);">
					The world<br />
					is now your<br />
					classroom
				</h3>
			</div>
			<div class="right-side" style="margin:0px">
                <img src="https://via.placeholder.com/620x420" alt="" />

				{{-- <img src="/web/images/flip_mobile01.png" alt="" /> --}}
			</div>
		</div>
	</body>

<script>
	// var fullPath = window.location.protocol + "//" + window.location.host + window.location.pathname + window.location.search
	// var deeplinkUrl = 'letsflip:' + "//" + window.location.host + window.location.pathname + window.location.search

	// const urlParams = new URLSearchParams(window.location.search);
	// if(urlParams.get('attempt') == null && urlParams.get('success') == null){
	// 	setTimeout(function(){ location.href = fullPath+'&attempt=1&success=true' ;},25);
	// 	location.href = deeplinkUrl;
	// }
</script>
</html>
