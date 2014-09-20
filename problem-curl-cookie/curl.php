<?php extension_loaded('curl') or die('you need to install and|or enable PHP cURL extension');

session_start(); // will set $_COOKIE[session_name()] = session_id()

// just for dumping
function d($v) {
	echo "\n<pre>\n";
	var_dump($v);
	echo "\n</pre>\n";
}

function baseurl($path = '') {
	extract($_SERVER);
	return 'http://' . str_replace('//','/', $HTTP_HOST.dirname($SCRIPT_NAME).'/'.$path);
}

// url target for cURL, target it to test.php file
$url = baseurl('test.php');

/**
 * =================================================
 * 3 SETS OF COOKIE VARIABLE THAT YOU SHOULD TESTS
 * ================================================= */

// 1) set of random cookie
// try to use it via cURL first, it's no problem
$cookie_random = array('foo'=>'bar', 'baz'=>'qux'); 

// 2) set of default session cookie
// use it in cURL will cause cURL error timeout
// because it's contain PHPSESSID
$cookie_of_this_session = $_COOKIE;

// 3) set of cookie that just contain PHPSESSID
// use it also will cause error timeout
$cookie_phpsessid = array();
$cookie_phpsessid[session_name()] = session_id();

/* ======================================================== *
 * TRY 3 SETS COOKIE VARIABLES ABOVE TOO SEE DIFFERENCES
 * -------------------------------------------------------- */
 $use_cookie = $cookie_random;
// -------------------------------------------------------- *

// ** you don't need to change scripts below **
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // buffer response
curl_setopt($curl, CURLOPT_TIMEOUT, 5); // request time limit (second)
curl_setopt($curl, CURLOPT_COOKIE, http_build_query($use_cookie,'','; ')); // will build string like "foo=bar; baz=qux;"

$response = curl_exec($curl); 

if(curl_errno($curl)) {
	// cURL error, show message
	die(curl_error($curl));
}

/*--- cURL success, dump output ----*/
echo "<h3>\$_COOKIE of This Session</h3>";
d($_COOKIE);

echo "<h3>\$_COOKIE in target page (access via cURL)</h3>";
d(json_decode($response, true));

