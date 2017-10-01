<?php


$result = ['one', 'two', 'three', 'four', 'five'];

$arr = [];

$arr['Status Code']= "200";
$arr['Status'] = "Completed";
$arr['data'] = $result;

$jsonr = json_encode($arr);
echo $jsonr;

?>