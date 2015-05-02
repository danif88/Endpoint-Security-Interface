<html>
<body>

<?php

include("encrypt.php");

$config = require 'config.php';

$user=$_POST["user"];
$password=$_POST["password"];

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"X-Forwarded-For: $ip"
  )
);

$context = stream_context_create($opts);
$result = file_get_contents( $config['uri_api'] ."/logIn?name=" . $user . "&encrypted=" . md5($password), false, $context);

$result_array=json_decode($result,true);

//$session=encrypt($result_array['data']);

$session=$result_array['data'];

if($result_array['status'] !== 1){
	echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "'>";
}
else{
	echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "/principal.php?session_id=" . $session . "'>";
}

exit();

?>

</body>
</html>
