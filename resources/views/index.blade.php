<?php
use Jenssegers\Agent\Agent;
$agent = new Agent(); 

$os = $agent->platform(); 

// if($os == 'ios') header('Location: letsflip://staging.getletsflip.com');
// if($os == 'AndroidOS') header('Location: https://staging.getletsflip.com');

// header('Location: https://staging.getletsflip.com');
 
?>


<a href="https://getletsflip.com">https://getletsflip.com</a>
<br>
<a href="letsflip://staging.getletsflip.com">letsflip://staging.getletsflip.com</a> 
<br>
<a href="https://staging.getletsflip.com">https://staging.getletsflip.com</a> 


<script>
@if($os == 'IOS' || $os == 'ios')
    window.location.href = "letsflip://staging.getletsflip.com"; 
@endif
</script>