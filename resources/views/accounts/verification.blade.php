<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="/web/css/style.css" type="text/css" rel="stylesheet" />
		<title>Verification Account</title>
	</head>
	<body>
		<header>
			<div class="logo">
				<img src="/web/images/logo.png" alt="" />
			</div>
		</header>
		<div class="page-container">
			<div class="left-side">
				<p>
					{{$message}}
				</p>
				<h3>
					The world<br />
					is now your<br />
					classroom
				</h3>
			</div>
			<div class="right-side">
				<img src="/web/images/flip_mobile01.png" alt="" />
			</div>
		</div>
	</body>

<script>
	var fullPath = window.location.protocol + "//" + window.location.host + window.location.pathname + window.location.search
	const urlParams = new URLSearchParams(window.location.search);	
	if(urlParams.get('attempt') == null && urlParams.get('success') == null){
		setTimeout(function(){ location.href = fullPath+'&attempt=1&success=true' ;},25);
		location.href = '{{$deeplink_url}}';
	}
</script>

</html>
