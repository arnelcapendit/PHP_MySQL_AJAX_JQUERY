<?php
date_default_timezone_set("Asia/Manila");
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "clsDB.php";
require_once "functions.php";
require_once "constants.php";

class LoginAPI extends DB{

 public function __construct(){
   parent::__construct(clsConstants::DBHost, clsConstants::DBUser, clsConstants::DBPass, clsConstants::DBName);
 }

 public function login($username, $password, $moduleid){
   $sql = "SELECT *
            FROM `tbl_user` a
              JOIN tbl_module_access b
               ON b.userid = a.id
          WHERE username = '".$username."'
          AND `password` = MD5('".$password."')
          AND a.status_flag = 1
          AND b.module_id = $moduleid
          AND b.status_flag = 1";
   return $this->query($sql);
 }

 public function headers($method = 'GET'){
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: $method");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 }

 public function response($response){
  $this->headers('POST');
  echo json_encode($response);
 }

 public function audit_trail($data){
  $str = date("Y-m-d H:i:s"). "[AUDIT]".':'. $data.PHP_EOL;
  file_put_contents("logs/audit-log-".date("Ymd").".log", $str, FILE_APPEND);
  return 0;
 }
}

?>
