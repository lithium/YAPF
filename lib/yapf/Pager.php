<?php

class Pager
{
  public function __construct($conn, $query, $values=array(), $page=1, $per_page=25)
  {
    $this->dbconn = $conn;
   
    $cc = $this->dbconn->query("SELECT COUNT(*) FROM ($query) as tempcount");
    $cc->next(); 
    $this->count = $cc->get(0);

    $offset = ($page-1)*$per_page;
    $query .= " LIMIT $per_page OFFSET $offset";
    $this->cursor = $this->dbconn->query($query,$values);

    $this->per_page = $per_page ? $per_page : 25;
    $this->page = $page;
    $this->last = max(1,ceil($this->count / $this->per_page));
  }
  public function getCount() { return $this->count; }
  public function getLastPage() { return $this->last; }
  public function getPage() { return $this->page; }
  public function getPerPage() { return $this->per_page; }
  public function getNextPage() { return $this->page + 1; }
  public function getPrevPage() { return $this->page - 1; }
  public function hasPages() { return $this->count > $this->per_page; }
  public function hasNextPage() { return $this->page < $this->last; }
  public function hasPrevPage() { return $this->page > 1; }

  public function next() { return $this->cursor->next(); }
  public function getRow() { return $this->cursor->getRow(); }
  public function get($idx) { return $this->cursor->get($idx); }

  
}
