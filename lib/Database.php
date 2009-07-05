<?php

class Database
{
  const DEFAULT_HOSTNAME='localhost';
  const DEFAULT_USERNAME='user';
  const DEFAULT_PASSWORD='';

  const USING_MYSQLI=true;

  private static $instance = null;
  private $conn = null;

  private function __construct($database,$host=null,$user=null,$pass=null)
  {
    if (is_null($host)) $host = self::DEFAULT_HOSTNAME;
    if (is_null($user)) $user= self::DEFAULT_USERNAME;
    if (is_null($pass)) $pass = self::DEFAULT_PASSWORD;
    if (self::USING_MYSQLI) {
      $this->conn = mysqli_connect($host,$user,$pass,$database);
    } else {
      $this->conn = mysql_connect($host,$user,$pass);
      mysql_select_db($database, $this->conn);
    }

  }
  public function __destruct()
  {
    if (self::USING_MYSQLI) {
      $this->conn->close();
    } else {
      mysql_close($this->conn);
    }
  }

  public static function instance($database=null,$host=null,$user=null,$pass=null)
  {
    if (is_null($database)) throw new Exception("Invalid Arguments");
    if (is_null(self::$instance)) {
      self::$instance = new Database($database,$host,$user,$pass);
    }
    return self::$instance;
  }
  public function query($query, $values=array())
  {
    $query = $this->replace_values($query,$values);
    #echo "\n<!-- $query -->\n";
    if (self::USING_MYSQLI) {
      $results = $this->conn->query($query);
    } else {
      $results = mysql_query($query, $this->conn);
    }
    if ($results === FALSE) 
      throw new Exception(self::USING_MYSQLI ? mysqli_error($this->conn) : mysql_error($this->conn));
    return new Cursor($results);
  }

  private function replace_values($query,$values=array())
  {
    if (strpos($query,'?') === FALSE) return $query;
    if (!is_array($values)) return FALSE;

    while (count($values) > 0) {
      $value = array_shift($values);
      $query = self::replace_once('?', $this->escape_value($value), $query);
    }
    return $query;
  }
  private static function replace_once($needle, $replace, $haystack)
  {
    if (($pos = strpos($haystack, $needle)) === FALSE) return $haystack;
    return substr_replace($haystack, $replace, $pos, strlen($needle));
  }
  private function escape_value($value)
  {
    if (is_numeric($value)) return $value;
    return '\''.(self::USING_MYSQLI ? $this->conn->real_escape_string($value) : mysql_real_escape_string($value, $this->conn)).'\'';
  }

}
