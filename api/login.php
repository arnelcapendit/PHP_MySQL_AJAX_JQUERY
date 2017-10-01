<?php
// required headers

 require_once "clsAPI.php";
 $api = new LoginAPI;
 $return;
 $response;
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $username = '';
  $password = '';
  $moduleid = '';
  if(isset($_REQUEST['username'])){
   $username = $_REQUEST['username'];
  }else{
   return $api->response(array('code' => 500, 'msg' => 'Username is required'));
  }

  if(isset($_REQUEST['password'])){
   $password = $_REQUEST['password'];
  }else{
   return $api->response(array('code' => 500, 'msg' => 'Password is required'));
  }

  if(isset($_REQUEST['moduleid'])){
   $moduleid = $_REQUEST['moduleid'];
  }else{
   return $api->response(array('code' => 500, 'msg' => 'Module ID is required'));
  }

  $data = $api->login($username, $password, $moduleid);
  if($data->hasRecords){
   $tokken = strtoupper(md5($username.time()));
   $response = array("code" => 200, "username" => $username, "login_tokken" => $tokken);
   $api->where(array("id" => $data->result[0]['id']));
   $api->update("tbl_user", array("login_tokken" => $tokken, 'user_ip' => $_SERVER['REMOTE_ADDR']));
   return $api->response($response);
  }else{
   return $api->response(array('code' => "404",'msg' => "Invalid Credentials"));
  }

 }else{
  return $api->response(array('code' => 400, "msg" => "Invalid Request"));
 }

?>
