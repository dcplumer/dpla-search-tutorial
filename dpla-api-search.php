<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>DPLA API Search Example with Results</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://dp.la/info/wp-content/uploads/2013/12/dpla-search-widget.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
echo '<div id="dpla_search_widget">';
echo '<img src="http://dp.la/info/wp-content/uploads/2013/12/dpla-search-widget-logo.png" width="190" height="52" longdesc="http://dp.la" />';
$self = $_SERVER['PHP_SELF'];
$form = '  
  <form name="dpla_widget" method="post" action="'.$self.'">  
  <input type="text" name="q" placeholder="Search DPLA">
  <input type="submit" name="append" value="Search" >
  </form>';
if(!empty($_POST['q']) && isset($_POST['append'])){
  $query = $_POST['q'];
  $cquery = str_replace(' ', '+', $query);
  $cquery = str_replace('?', '%26', $cquery);
  $cquery = str_replace('&', '%3F', $cquery);
  $url = "http://api.dp.la/v2/items?q=".$cquery.'&api_key=YOURAPIKEY0000000000000000000001';
  echo $form;
}else{
  echo $form; 
  }
echo '</div>';
  
// results code developed by Eric Frierson, efrierson@ebsco.com. Modified by Danielle Cunniff Plumer, dcplumer@gmail.com.
// send the API request, using the URL generated above

$session = curl_init($url);                            // Open the Curl session
curl_setopt($session, CURLOPT_HEADER, false);          // Don't return HTTP headers
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);   // Do return the contents of the call
$xml = curl_exec($session);                            // Make the call
curl_close($session);                                  // And close the session
  
// create a json object to traverse
$result = json_decode($xml);

// get number of results
$numresults = intval($result->count);
$count = 0;

if ($numresults > 0) {
  echo 'Your search for "'.$query.'" returned '.$numresults.' results. Results 1-10:';
  echo '<table border="0" cellspacing="3">';
  foreach ($result->docs as $doc) {

  // for each result, show an image, a title, and have both link to the item in DPLA
  // also show name of institution (dataProvider), but no link

    $count++;
    if ($count <= 10) {
      $link = $doc->isShownAt;
      $title = $doc->sourceResource->title;
	  $provider = $doc->dataProvider;

      // sometimes I find an array in the title node - this will grab the first title
      if (is_array($title)) {
        $title = $title[0];
      }

      // sometimes I find an array in the provider node - this will grab the first provider
      if (is_array($provider)) {
        $provider = $provider[0];
      }

      // if there is an image, it's here
      if (isset($doc->object)) {
        $image = $doc->object;
      }
    
      // if not, show placeholder image
      if (!(isset($image))) {
        $image = "http://dp.la/assets/icon-text.gif";
      }
    
      // display item
		    echo '<tr><td><a href="'.$link.'" target="_blank"><img src="' . $image . '" width="55" /></a></td><td><a href="' . $link . '" target="_blank">' . $title . '</a><br />' . $provider . '</td></tr>';
      }
    }
    echo '</table>';

    // provide link to jump out to DPLA search, including query
    echo '<a href="http://dp.la/search?q='.$query.'" target="_blank">See full results for your query "'.$cquery.'" at the Digital Public Library of America</a>';

  } else {

  // no results case. If no results, return error message. Also include a null response for the initial condition, pre-query.
  if (isset($cquery)) {
    echo '<div id="dpla_search_results_dcplumer">Your search for "'.$query.'" returned no results. Please search again.</div>';
  } else {
    echo ' ';
  }
  }
?>
</body>
</html>

