<?php

class SeriesEntity extends Entity
{
  public static $TABLE_NAME="series";
  public static $PK="id";
  public static $FIELDS = array (
    'name',
    'version',
    'start_year',
    'publisher_id',
  );
}
