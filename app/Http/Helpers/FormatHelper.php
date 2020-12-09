<?php
  
function dateFormat($date){
    return \Carbon\Carbon::parse($date)->format("D ,M Y");   
}