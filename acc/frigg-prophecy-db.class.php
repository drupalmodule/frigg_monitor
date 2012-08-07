<?php
define('FriggDBHost', 'localhost');
define('FriggDBUser', 'root');
define('FriggDBPswd', 'H4mmer0fLight');
define('FriggDBName', 'pcm_ipdb');
define('lockDir', '/var/lock/frigg-prophecy');

require_once 'DBBase.class.php';
class prophecy extends DBBase {

  public function frigg_get_status($sql) {
    $Result = $this->getSingle($sql);
    $RetStr =  $Result['Variable_name'] . ": " . $Result['Value'] . "  ";
    echo $RetStr;
    }

  public function frigg_full_monty($MontySql) {
  @reset ($MontySql);
  while (@list(,$sql) = @each ($MontySql))
    {
      $Result = $this->getSingle($sql);
      $RetStr =  $Result['Variable_name'] . ": " . $Result['Value'] . "  ";
      echo $RetStr;
    }
  return;
  }

  public function get_bad_ip_num($BadIPsql) {
    $Result = $this->getSingle($BadIPsql);
    $Rezult =  $Result['count(badid)'];
	 $RetStr = "BadIPs: " . $Rezult;
    echo $RetStr;
  }

  public function frigg_insert_queries() {
    $sql = '';
    return;
  }
  public function frigg_blank(){
    $sql = '';
    return;
  }
	public function __construct($debug=false) {
		$this->MultipleSql = '';
		$this->MultipleSqlSep = '';
		parent::__construct(FriggDBHost, FriggDBUser, FriggDBPswd, FriggDBName, False);
	}


public function lockfile($action) {
  if(is_dir(lockDir) && $action=="check") {
    return 1;
  }
  if (!is_dir(lockDir) && $action=="set") {
    mkdir(lockDir, 0755);
  }
  if (is_dir(lockDir) && $action=="unset") {
    rmdir(lockDir);
  }
  return 0;
  }

  public function parseArgs($argv){
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


}
