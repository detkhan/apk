<?php
class json
{
  function addjson($json_data,$jsoncallback)
  {
    $callback = $jsoncallback;
    $data= json_encode($json_data);
    $datajson=$callback.'('.$data.');';
    return $datajson;
  }//function addjson
}//class


?>
