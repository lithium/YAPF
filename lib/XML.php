<?php

class XML
{
  public static function toArray($xmldata) {
    $ret = array();
    $xml = simplexml_load_string($xmldata);
    if (!$xml) return $ret;
    foreach($xml->attributes() as $a => $v) {
      $ret[(string)$a] = (string)$v;
    }
    foreach($xml as $node) {
      $name = $node->getName();
      $ret[$name] = self::parse_node($node);
    }
    return $ret;
  }
  private static function parse_node($xml_node)
  {
    $ret = (string)$xml_node;
    $attributes = $xml_node->attributes();
    if ($attributes) {
      $ret = array();
      foreach($xml_node->attributes() as $a => $v) {
        $ret[(string)$a] = (string)$v;
      }
    }
    $children = $xml_node->children();
    if ($children) {
      if (!is_array($ret)) $ret = array();
      foreach ($xml_node->children() as $child) {
        $child_name = $child->getName();
        if (!isset($ret[$child_name])) $ret[$child_name] = array();
        array_push($ret[$child_name], self::parse_node($child));
      }
    }
    return $ret;
  }
}
