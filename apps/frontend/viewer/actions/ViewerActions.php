<?php
class ViewerActions extends Actions 
{
  public function executeIndex($request,$response)
  {
    $this->publishers = PublisherEntity::find();
    $this->series = SeriesEntity::find();
  }
}
