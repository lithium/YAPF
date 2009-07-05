<?php

class Cursor
{
  private $results;
  private $cur_row;
  public function __construct($mysql_results)
  {
    $this->results = $mysql_results;
  }
  public function next($type=MYSQL_BOTH)
  {
    if (!$this->results)
      throw new Exception("Invalid Cursor");
    if (Database::USING_MYSQLI) {
      $this->cur_row = mysqli_fetch_array($this->results, $type);
      return (!is_null($this->cur_row));
    } else {
      $this->cur_row = mysql_fetch_array($this->results, $type);
      return ($this->cur_row !== FALSE);
    }
  }
  public function get($idx)
  {
    if (!array_key_exists($idx, $this->cur_row))
      throw new Exception("Invalid field");
    return $this->cur_row[$idx];
  }
  public function getRow() { 
    return $this->cur_row; 
  }
  public function getCount() 
  {
    return Database::USING_MYSQLI ? mysqli_num_rows($this->results) : mysql_num_rows($this->results);
  }
}
