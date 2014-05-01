<!doctype html>
<html>
<head>
<title>Request your DPLA API KEY</title>
</head>

<body>
<?php
  echo '<p>If you do not have access to <code>curl</code> (on Windows, for example), you can request your API key using the form below.';

  echo '<p>Enter your email, and the form will send a request to <code>http://api.dp.la/v2/api_key/</code>. The response should look something like:</p>';

  echo '<blockquote><code>{"message": "API key created and sent via email. Be sure to check your Spam folder, too."}</code></blockquote>';

$self = $_SERVER['PHP_SELF'];
$form = '  
  <form name="dpla_api_request" method="post" action="'.$self.'">  
  <input type="text" name="email" placeholder="Enter your email address">
  <input type="submit" name="append" value="Submit" >
  </form>';
if(!empty($_POST['email']) && isset($_POST['append'])){
  $email = $_POST['email'];
  echo $form;
  echo '<p>Your email: '.$email.'</p>';
  echo '<form action="http://api.dp.la/v2/api_key/'.$email.'" method="POST">';
  echo '<input type="submit" title="Request Your Key" value="Request Your Key" />';
  echo '</form>';

}else{
  echo $form; 
  }


?>
</body>
</html>
