<?php 

class DBBase {
	public function execute($sql){
		$results = mysql_query($sql);
		if($results === FALSE){
			throw new Exception(mysql_error());
		}
		return $results;
	}
	public function executeTraped($sql){
		$errMsg = '';
		try {
			$status = $this->execute($sql);
		} catch (Exception $e) {
			$errMsg = $e->getMessage();
		}
		return $errMsg;
	}
  public function getSingle($sql){
		$results = mysql_query($sql);
		if($results){
			return  $this->getRow($results);
		}
		return $results;
	}
	
	public function getRow($results) {
		$row = mysql_fetch_array($results, MYSQL_ASSOC);
		if( !$row){
			return $row;
		}
		foreach (array_keys($row) as $key) {
			$row[$key] = stripslashes($row[$key]);
		}
		return $row;
	}
	
  public function mysql_bind_values($vals) {
		$sql = '';
		$sep = '';
	    foreach ($vals as $name => $val) {
			$sql = $sql . $sep . $name . "='" .  mysql_real_escape_string($val) . "'";
			$sep  = ', ';
	    }
		return $sql;
	}
	
  public function __construct($host, $user, $pswd, $dbName, $debug=false){
		$this->dbc = mysql_connect($host, $user, $pswd);
		if (!$this->dbc) {
			if($debug){
				echo("connect failed" . mysql_error());
  	    echo "\n" . $host . ' ' . $user . ' ' . $pswd . "\n";
			}
      die();
    }
    $this->db_selected = mysql_select_db($dbName, $this->dbc);
    if(!$this->db_selected){
		  if($debug){
					echo "Can\'t use " . $dbName . ' : ' . mysql_error();
	  	    echo "\n" . $host . ' ' . $user . ' ' . $pswd . "\n";
		  }
	    die();
    }
	}
}
