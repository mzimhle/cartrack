<?php
// When the website is being put sent for checking, enable this.
// Otherwise while still in development, show it.
// I do not want to risk errors showing only notices must show
// error_reporting(0);
// Lets try to avoid browser caching any page.
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/* Get the settings. */
$settings = parse_ini_file("settings.ini", true);

if(isset($settings[$_SERVER['HTTP_HOST']])) {
	$config = $settings[$_SERVER['HTTP_HOST']];
} else {
	echo 'Site configuration missing...';
	exit;
}
/* Setup Database Connection. */
// P.S. This will only be used via the API site, so all database connections will be done via an API, so every error must be returned as the standard
// return json message, which is : {"code":200,"message":"Any message"}
try {
	// Connect to the database
	$conn = pg_connect("host={$config['host']} port={$config['port']} dbname={$config['database']} user={$config['user']} password={$config['password']}");
	// Check if the connectin was successful
	if(!$conn) {
        echo json_encode(array('code' => 500, 'message' => 'Could not connect to the database, please check the connection string or credentials.'));
        exit;
	}
} catch (Exception $e) {
	// Incase of any catched errors, lets cater for them also.
	echo json_encode(array('code' => 500, 'message' => 'Could not connect to the database, please check the connection string or credentials.'));
	exit;
}
?>