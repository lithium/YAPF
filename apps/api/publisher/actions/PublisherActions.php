<?php
class PublisherActions extends Actions 
{
  public function executePut($request,$response)
  {
    $values = $request->getParameters(PublisherEntity::$FIELDS);

    $publisher = PublisherEntity::fromPk($values['id'], true);
    $publisher->hydrate($values);
    $publisher->save();
    $response->write($publisher->toXML());
  }
  public function executeGet($request,$response)
  {
    $values = $request->getParameters(PublisherEntity::$FIELDS);

    $publishers = PublisherEntity::find($values);
  
    $response->write($publishers->toXml());
  }
}
