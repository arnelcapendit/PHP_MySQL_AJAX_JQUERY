
<?php
/**
 * CLASS DATABASE
 *
 * @package LIBRARY
 * @author jaristain
 * @version 1.0.0
 * @name db.class.php
 * Description: Platform Active Records
 */
 class DB {

  protected $objhost;
  protected $objusername;
  protected $objpassword;
  protected $objdbname;
  protected $objDBcon;
  protected $objresult;
  protected $objtotal;

  protected $result;
  protected $records;

  protected $column     = "";
  protected $from     = "";
  protected $group_by    = "";
  protected $costumize_condition = "";

  protected $where     = array();
  protected $where_or   = array();
  protected $where_in   = array();
  protected $where_not_in = array();
  protected $between     = array();
  protected $join     = array();

  protected $isJoin     = 0;
  protected $limit    = 0;
  protected $offset    = 0;
  protected $env       = "";
  protected $showQuery    = FALSE;
  protected $hasCondition = FALSE;

    public function __construct($host, $user, $pw, $db, $env = "PROD", $showQuery = TRUE) {
        $this->objhost       = $host;
        $this->objusername     = $user;
        $this->objpassword     = $pw;
        $this->objdbname     = $db;
        $this->env         = $env;
        $this->showQuery     = $showQuery;

        $conn = $this->db_connect();

        if(!$conn[0]){
          logger($conn['msg'], "DB Error");
          exit;
        }else{
          logger(sprintf("Connected to DB: %s", $host));
          logger(sprintf("DB Selected: %s", $db));
        }
    }


  protected function db_connect(){
    $this->objDBcon     = new mysqli($this->objhost, $this->objusername, $this->objpassword, $this->objdbname);
    // Check connection
    if ($this->objDBcon->connect_errno)
      {
      return array(false, "msg" => "Failed to connect to MySQL: " . $this->objDBcon->connect_error);
      }
    return array(true);
  }

  public function get($tbl = "", $dbName = "", $order = array()){
    $strColumns     = $this->column ? $this->column : "*";
    $strTableName     = $tbl;
    $strDBName      = $dbName <> "" ? $dbName : $this->objdbname;
    $strOffset       = $this->offset ?   " OFFSET "    . $this->offset : false;
    $strLimit       = $this->limit  ?   " LIMIT "    . $this->limit : false;
    $strGroup       = $this->group_by ? " GROUP BY " . $this->group_by : false;
    $strWhere       = "";
    $strOrder      = "";

    if($this->hasCondition === TRUE){
      $strWhere = $this->__condition($this->where);
    }
    if(!empty($order)){
      foreach($order as $k=>$val){
        if($k == "0"){
          $strOrder = sprintf(" ORDER BY `%s` ASC", $val);
        }else{
          $strOrder = sprintf(" ORDER BY `%s` %s", $k, $val);
        }
      }
    }

    $arrArgs = array($strColumns, $strDBName, $strTableName, $strWhere, $strGroup, $strOrder, $strLimit, $strOffset);
    $sql = vsprintf("SELECT %s FROM `%s`.`%s` %s %s %s %s %s", $arrArgs);
    return $this->query($sql);
  }

  public function insert_select($strInsertTbl = "", $strInsertCol, $strSelectTbl = ""){

    $strColumns     = $this->column ? $this->column : "*";
    $strTableName     = $strSelectTbl;
    $strWhere       = "";
    $strOffset       = $this->offset ?   " OFFSET "    . $this->offset : false;
    $strLimit       = $this->limit ?   " LIMIT "    . $this->limit : false;
    $strGroup       = $this->group_by ? " GROUP BY " . $this->group_by : false;

    if($this->hasCondition === TRUE){
      $strWhere = $this->__condition($this->where);
    }

    $arrArgs = array($strColumns, $strTableName, $strWhere, $strGroup, $strLimit, $strOffset);
    $strInsertSQL = sprintf("INSERT INTO %s (%s)", $strInsertTbl, $strInsertCol);
    $strSelSQL = vsprintf("SELECT %s FROM %s %s %s %s %s", $arrArgs);

    $strSQL = $strInsertSQL . " " . $strSelSQL;
    return $this->query($strSQL);
  }

  public function insert_select_join($strInsertTbl = "", $strInsertCol, $strSelectTbl = ""){

    $strColumns     = $this->column ? $this->column : "*";
    $strTableName     = $strSelectTbl;
    $strWhere       = "";
    $arrJOIN       = $this->join;
    $strJOINTbl      = $arrJOIN['tbl'];

    if($this->hasCondition === TRUE){
      $strWhere = $this->__condition($this->where);
    }
    $strInsertSQL = sprintf("INSERT INTO %s (%s)", $strInsertTbl, $strInsertCol);

    $arrJoin   = array($arrJOIN['type'], $strJOINTbl, $arrJOIN['key']['fk'], $arrJOIN['key']['pk']);
    $strJOIN   = vsprintf("%s JOIN %s ON %s = %s", $arrJoin);

    $arrArgs1  = array($strColumns, $strTableName, $strJOIN, $strWhere);
    $strSelSQL = vsprintf("SELECT %s FROM %s %s %s", $arrArgs1);

    $strSQL = $strInsertSQL . " " . $strSelSQL;
    return $this->query($strSQL);
  }

  private function checkErrors($arrResult){
    $isBool = false;
    $strErrResult = "";
    if(!$arrResult){
      $isBool = true;
      $strErrResult = "Database error Error description: " . mysqli_error($this->objDBcon);
    }
    return array("hasError" => $isBool, "msg" => $strErrResult);
  }

  public function insert($tbl = "", $data = array(), $dbName = ""){
    $arrCols   = array();
    $strCols   = "";

    $arrVal   = array();
    $strVal   = "";

    $strDBName  = $dbName <> "" ? $dbName : $this->objdbname;

    foreach($data as $key=>$val){
      $arrCols[] = "`{$key}`";
      $arrVal[]  = "'{$val}'";
    }

    $strCols = implode(",", $arrCols);
    $strVal = implode(",", $arrVal);

    $arrArgs = array($strDBName, $tbl, $strCols, $strVal);
    $sql = vsprintf("INSERT INTO `%s`.`%s`(%s) VALUES(%s);", $arrArgs);

    return $this->query($sql);
  }

  public function delete($tbl = "", $dbName = ""){

    $strWhere       = "";
    $strDBName      = $dbName <> "" ? $dbName : $this->objdbname;

    if($this->hasCondition === TRUE){
      $strWhere = $this->__condition($this->where);
    }

    $arrArgs = array($strDBName, $tbl, $strWhere);
    $sql = vsprintf("DELETE FROM `%s`.`%s` %s", $arrArgs);

    return $this->query($sql);
  }

  public function update($tbl = "", $data = array(), $dbName = ""){

    $arrConditions     = $this->where;
    $strWhere       = "";
    $strSet        = "";
    $arrSet        = array();
    $strDBName      = $dbName <> "" ? $dbName : $this->objdbname;

    if(!empty($arrConditions)){
      $strWhere = $this->__condition($arrConditions);
    }
    if(!empty($data)){
      foreach($data as $k=>$v){
        $values = is_int($v) ? $v : $this->objDBcon->real_escape_string($v);
        $arrSet[] = "`{$k}` = " . (is_int($values) ? $values : "'{$values}'");
      }
      $strSet = implode(",", $arrSet);
    }

    $arrArgs = array($strDBName, $tbl, $strSet, $strWhere);
    $sql = vsprintf("UPDATE `%s`.`%s` SET %s %s", $arrArgs);

    return $this->query($sql);
  }

  public function query($query = "", $opt = TRUE){

    if($this->showQuery === TRUE && $opt === TRUE){
      logger($query, "QUERY");
    }

    $arrQuery   = explode(" ", $query);
    $isSelect    = (strtolower($arrQuery[0]) == "select"  ? TRUE : FALSE);
    $msc     = microtime(true);
    $arrResult   = $this->objDBcon->query($query);
    $msc     = microtime(true)-$msc;
    $isError   = $this->checkErrors($arrResult);
    $this->column   = null;
    $this->limit   = null;
    $this->offset = null;
    $this->group_by = null;
    if($isError['hasError'] === FALSE){
      if($isSelect === TRUE){
        $arrRows = array();
        $hasRecords = 0;
        while($rows = $arrResult->fetch_assoc()){
          $arrRows[] = $rows;
          $hasRecords = 1;
        }
        $arrReturn =  array('hasRecords' => $hasRecords, 'rows' => $arrResult->num_rows, 'result' => $arrRows,'time' => number_format($msc, 2));
      }else{
        $arrReturn =  array('affected_rows' => $this->objDBcon->affected_rows,'time' => $msc);
      }
      $this->result = new stdClass();
      foreach($arrReturn as $key=> $value){
        $this->result->$key = $value;
      }
      return $this->result;
    }else{
      logger($isError['msg'], "DB Error");
      exit;
    }
  }

  private function __condition($arrConditions = array()){
    $strWhere     = "";

    $strCostumeCondition = $this->costumize_condition ? $this->costumize_condition : false;
    $arrWhereIn   = $this->where_in ? $this->where_in : false;
    $arrWhereNotIn   = $this->where_not_in ? $this->where_not_in : false;
    $arrWhereOR   = $this->where_or ? $this->where_or : false;
    $arrBetween   = $this->between ? $this->between : false;

    $strWhere .= "WHERE 1";
    if(!empty($arrConditions)){
      foreach($arrConditions as $key=>$value){
        $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
        $arrCon = explode(" ", $key);
        $strCon = (count($arrCon) > 1 ? $arrCon[1] : "=");
        $strkey = (count($arrCon) > 1 ? $arrCon[0] : $key);
        $strWhere .= " AND {$strkey} {$strCon} " .(is_int($value) ? $value : "'{$value}'");
      }
    }

    if(!empty($arrWhereOR)){
      foreach($arrWhereOR as $key=>$value){
        $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
        $arrCon = explode(" ", $key);
        $strCon = (count($arrCon) > 1 ? $arrCon[1] : "=");
        $strkey = (count($arrCon) > 1 ? $arrCon[0] : $key);
        $strWhere .= " OR {$strkey} {$strCon} " .(is_int($value) ? $value : "'{$value}'");
      }
    }

    if(!empty($arrWhereIn)){
      foreach($arrWhereIn as $key=>$value){
        $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
        $strWhere .= " AND {$key} IN({$value})";
      }
    }

    if(!empty($arrWhereNotIn)){
      foreach($arrWhereNotIn as $key=>$value){
        $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
        $strWhere .= " AND {$key} NOT IN({$value})";
      }
    }

    if(!empty($arrBetween)){
      $x = 0;
      foreach($arrBetween as $key=>$value){
        if($x == 0){
          $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
          $strWhere .= " AND {$key} BETWEEN " . (is_int($values) ? $value : "'{$values}'");
        }else{
          $values = is_int($value) ? $value : $this->objDBcon->real_escape_string($value);
          $strWhere .= " AND " .(is_int($values) ? $value : "'{$values}'");
        }
      $x++;
      }
    }

    if(!empty($strCostumeCondition)){
      $strWhere .= " ".$strCostumeCondition;
    }

    $this->where     = array();
    $this->where_not_in = array();
    $this->where_or   = array();
    $this->between     = array();
    $this->where_in   = array();
    $this->hasCondition = FALSE;
    $this->costumize_condition = "";

    return $strWhere;
  }

  public function column($column){
    $this->column = $column;
    return $this->column;
  }

  public function truncate($tbl){
    $sql = "TRUNCATE TABLE {$tbl}";
    $this->query($sql);
    return true;
  }

  public function where($arrConditions){
    $this->hasCondition = TRUE;
    $this->where     = $arrConditions;
    return $this->where;
  }

  public function where_or($arrConditions){
    $this->hasCondition = TRUE;
    $this->where_or   = $arrConditions;
    return $this->where_or;
  }

  public function where_in($arrConditions){
    $this->hasCondition = TRUE;
    $this->where_in   = $arrConditions;
    return $this->where_in;
  }

  public function where_not_in($arrConditions){
    $this->hasCondition = TRUE;
    $this->where_not_in = $arrConditions;
    return $this->where_not_in;
  }

  public function limit($limit, $offset = 0){
    $this->hasCondition = TRUE;
    $this->limit     = $limit;
    $this->offset     = $offset;
    return $this;
  }

  public function group_by($col){
    $this->hasCondition = TRUE;
     $this->group_by   = $col;
   return $this->group_by;
  }

  public function between($arrBetween = array()){
    $this->hasCondition = TRUE;
    $this->between     = $arrBetween;
   return $this->between;
  }

  public function costumize_condition($str = ""){
    $this->hasCondition = TRUE;
    $this->costumize_condition = $str;
    return $this->costumize_condition;
  }

  public function join($tbl, $key, $type = ""){
    $this->isJoin = 1;
    $arrJoin = array(
      "tbl" => $tbl,
      "key" => $key,
      "type" => ($key <> "" ? $type : false)
    );
    $this->join = $arrJoin;

    return $this->join;
  }

  public function __destruct(){
    $this->objDBcon->close();
    unset($this);
  }
}

?>
