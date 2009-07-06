<?php

class CrewActions extends Actions 
{
  public function executePut($request,$response)
  {
    $values = $request->getParameters(CrewEntity::$FIELDS);

    $crew = CrewEntity::fromPk($values['id'], true);
    $crew->hydrate($values);
    $crew->save();
    $response->write($crew->toXML());
    return FALSE;
  }
  public function executeGet($request,$response)
  {
    $values = $request->getParameters(CrewEntity::$FIELDS);

    $crews = CrewEntity::find($values);

    $response->write("<crews>");
    foreach($crews as $crew) {
      $response->write( $crew->toXml() ); 
    }
    $response->write("</crews>");
    return FALSE;
  }
}
