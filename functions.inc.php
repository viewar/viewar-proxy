<?

/*
 * function getParameters
 *
 * by MM
 * returns url parameters. first we split the url by / and then we extract key:value
 *
 *
 */


function getParameters($url) {
    $parameters = array();
    $urlParts = explode("/", $url);
    foreach ($urlParts as $part){
      $paramParts = explode(":", $part);
      $parameters[$paramParts[0]] = @$paramParts[1] ? $paramParts[1] : true;
    }

    return $parameters;
}


// debug function
function debug($data, $stop = false) {

     
     if(is_array($data))
     {
          echo "<pre>-----------------------\n";
          print_r($data);
          echo "-----------------------</pre>";
     }
     elseif (is_object($data))
     {
          echo "<pre>==========================\n";
          var_dump($data);
          echo "===========================</pre>";
     }
     else
     {
          echo "=========&gt; ";
          var_dump($data);
          echo " &lt;=========";
     }


}


?>