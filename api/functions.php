<?php

function logger($str = "", $type = ""){
 $str = date("Y-m-d H:i:s"). ":[".($type <> "" ? strtoupper($type) : "LOGS")."]: $str".PHP_EOL;
 file_put_contents("logs/api-log-".date("Ymd").".log", $str, FILE_APPEND);
}

function printArr($arr = array()){
  echo "\n";
  print_r($arr);
  echo "\n";
}
?>
