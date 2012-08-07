#!/usr/bin/php
<?php

$Argz = parseArgs($argv);
if (!isset($Argz[0])) {
	echo "No Address Specified!!";
	exit(1);
}


 $result=shell_exec("ssh -n -ouser=root $Argz[0] /root/dev/frigg_monitor/acc/frigg-prophecy.php $Argz[1]");
	print $result;

 exit(0);

function parseArgs($argv){
    array_shift($argv);
    $out = array();
    foreach ($argv as $arg){
      if (substr($arg,0,2) == '--'){
        $eqPos = strpos($arg,'=');
        if ($eqPos === false){
          $key = substr($arg,2);
          $out[$key] = isset($out[$key]) ? $out[$key] : true;
        } else {
          $key = substr($arg,2,$eqPos-2);
          $out[$key] = substr($arg,$eqPos+1);
        }
      } else if (substr($arg,0,1) == '-'){
        if (substr($arg,2,1) == '='){
          $key = substr($arg,1,1);
          $out[$key] = substr($arg,3);
        } else {
          $chars = str_split(substr($arg,1));
          foreach ($chars as $char){
            $key = $char;
            $out[$key] = isset($out[$key]) ? $out[$key] : true;
          }
        }
      } else {
        $out[] = $arg;
      }
    }
    return $out;
  }


