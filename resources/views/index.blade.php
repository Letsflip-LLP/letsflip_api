<?php
use Jenssegers\Agent\Agent;
$agent = new Agent(); 

$os = $agent->platform(); 

if($os == 'ios') header('Location: letsflip://staging.getletsflip.com');
if($os == 'AndroidOS') header('Location: https://staging.getletsflip.com');

header('Location: https://getletsflip.com');

exit;
?>