<?php

class Entity
{
  private $values=array();
  public function __construct($init_values=array()) { 
    $this->hydrate($init_values);
  }
  public function __get($name) {
    if (!array_key_exists($name, static::$FIELDS)) 
      throw new Exception("Field '$name' not found");
    if (array_key_exists($name, $this->values))
      return $values[$name];
    return null;
  }
  public function __set($name, $value) {
    $this->values[$name] = $value;
  }
  public function toArray() { 
    return $this->values;
  }
  public function hydrate($values) {
    foreach($values as $k => $v) {
      $this->$k = $v;
    }
  }
  public function save() {
    $dbh = Database::instance();
    if (!array_key_exists(static::$PK, $this->values)) {
      foreach($this->values as $field => $val) {
        $fields[] = "`$field`";
        $vals[] = "?";
        $values[] = $val;
      }
      $dbh->query("INSERT INTO `".static::$TABLE_NAME."` (".implode(",",$fields).") VALUES (".implode(",",$vals).")",$values);
      $this->values[static::$PK] = $dbh->insert_id();
    }
    else {
      foreach($this->values as $field => $val) {
        $fields[] = "`$field`=?"; 
        $values[] = $val;
      }
      $values[] = $this->values[static::$PK];
      $dbh->query("UPDATE `".static::$TABLE_NAME."` SET ".implode(",",$fields)." WHERE `".static::$PK."`=?",$values);
    }
  }
  public function toXml() {
    $fields = $this->values;
    $out = "<".static::$TABLE_NAME;
    if (array_key_exists(static::$PK, $fields)) {
      $out .= " ".static::$PK.'="'.$fields[static::$PK].'"';
      unset($fields[static::$PK]);
    }
    $out .= ">";
    foreach($fields as $f => $v) {
      if ($v)
        $out .= "<$f>$v</$f>";
      else
        $out .= "<$f/>";
    }
    $out .= "</".static::$TABLE_NAME.">";
    return $out;
  }


  public static function find($fields=array()) {
    $dbh = Database::instance();
    $values = array();
    $where = array();
    foreach($fields as $f => $v) {
      $where[] = "`$f`=?";
      $values[] = $v;
    }
    if (count($where))
      $where = "WHERE ".implode(',',$where);
    else
      $where = '';
    $cursor = $dbh->query("SELECT * FROM `".static::$TABLE_NAME."` $where",$values);
    $cls = get_called_class();
    $ret = array();
    while($cursor->next()) {
      $ret[] = new $cls($cursor->getRow());
    }
    return $ret;
  }
  public static function fromPk($pk, $create=false) {
    $dbh = Database::instance();
    $cursor = $dbh->query("SELECT * FROM `".static::$TABLE_NAME."` WHERE `".static::$PK."`=?",array($pk));
    $cls = get_called_class();
    $ret = new $cls();
    if ($cursor->next()) {
      $ret->hydrate($cursor->getRow());
    } elseif (!$create) {
      return null;
    }
    return $ret;
  }
  public static function fromArray($array) {
    $cls = get_called_class();
    $ret = new $cls(); 
    $ret->hydrate($array);
    return $ret;
  }
  
}
