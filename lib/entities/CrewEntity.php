<?php

class CrewEntity extends Entity
{
  public static $TABLE_NAME="crew";
  public static $PK = 'id';
  public static $FIELDS = array(
    'id',
    'first',
    'last',
    'birth_date',
    'death_date',
  );
}
