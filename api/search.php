<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'request/request.php';
// Return data.
$return = array('code' => 500, 'message' => '');
// Make sure the name of the table to be queueed is selected
if(!isset($_GET['entity'])) {
	$return['message'] = 'Please select the entity to be requested';
	echo json_encode($return);
	exit;
} else if(trim($_GET['entity']) == '') {
	$return['message'] = 'Please select the entity to be requested';
	echo json_encode($return);
	exit;
} else if(!preg_match('/^[a-z]+/', trim($_GET['entity']))) {
	$return['message'] = 'Please make sure that the name parameter only has letters';
	echo json_encode($return);
	exit;
} else if(trim($_GET['entity']) != Table::MEMBER && trim($_GET['entity']) != Table::ANIMAL) {
	$return['message'] = 'Invalid entity given.';
	echo json_encode($return);
	exit;
}
// Minimum is at least this parameter should be present when data is being accessed on this page.
if(!isset($_GET['filter_search'])) {
	$return['message'] = 'Please make sure you have added the parameter before using this link.';
	echo json_encode($return);
	exit;
}
// Get the object
$requestObject = new Request(trim($_GET['entity']));
// Setup the filter 
$filter		= array();
$filter[]	= array('filter_search' => trim($_GET['filter_search']));
// Setup pagination.
$start 	= isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
$length	= isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 100;
// Get the data.
$return = $requestObject->_object->paginate($start, $length, $filter);
// Lets get the data if any.
echo json_encode($return);
exit;
$return = $requestObject = $filter = $start = $length = null;
unset($return, $requestObject, $filter, $start, $length);
?>