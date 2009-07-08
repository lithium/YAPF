<?php

class IssueEntity extends Entity
{
  public static $TABLE_NAME="issue";
  public static $PK="id";
  public static $FIELDS = array (
    'series_id',
    'issue_no',
    'print_date',
    'print_run',
    'cover',
    'story_arc',
    'arc_no',
    'condition',
  );
}
