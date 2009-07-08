<?php
class JobEntity extends Entity
{
  public static $TABLE_NAME="job";
  public static $PK = 'id';
  public static $FIELDS = array(
    'name',
  );
}
