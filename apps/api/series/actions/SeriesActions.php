<?php
class SeriesActions extends Actions 
{
  public function executePut($request,$response)
  {
    $values = $request->getParameters(SeriesEntity::$FIELDS);
    $series = SeriesEntity::fromPk($values[SeriesEntity::$PK], true);
    $series->hydrate($values);
    $series->save();
    $response->write($series->toJson());
  }
  public function executeGet($request,$response)
  {
    $values = $request->getParameters(SeriesEntity::$FIELDS);
    $serieses = SeriesEntity::find($values);

    $json_cb = $request->getParameter('jsoncallback');
    $response->write($json_cb.'('.$serieses->toJson().')');
    return FALSE;
  }
}
