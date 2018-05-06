<?php
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

// Receive data from client
$data = json_decode(file_get_contents('php://input'), true);

$session_id = $data['session_id']

$isComplete = true;
$errorMessage = '';
$response = array();
$status = '';


if($isComplete) {
	$query_scheduled = "SELECT session_id FROM scheduled_sessions WHERE session_id='$session_id';";
    $query_available = "SELECT id FROM available_sessions WHERE id='$session_id';";
	$result1 = queryDB($query_scheduled, $db);
	$result2 = queryDB($query_available, $db);
	if(nTuples(result1) === 0) {
		$status = 'error';
		$errorMessage .= "Session_id $session_id does not match any record in the 'scheduled_sessions' table";
	}
	if(nTuples(result2) === 0) {
		$status = 'error';
		$errorMessage .= "Session_id $session_id does not match any record in the 'available_sessions' table";
	}
}

if ($isComplete) {
	$query1 = "DELETE FROM scheduled_sessions WHERE session_id='$session_id';";
	queryDB($query1, $db);
	$query2 = "DELETE FROM available_sessions WHERE id='$session_id';";
	queryDB($query2, $db);	
	$status = 'success';
	$response['status'] = $status;
} else {
	// Something's wrong - send back an error
	ob_start();
	var_dump($data);
	$postDump = ob_get_clean();
	
	$response = array();
	$status = 'error';
	$response['message'] = $errorMessage;
	$response['status'] = $status;
	// Split this off so it doesn't show up for the user
	$response['postDump'] = $postDump;
}

// Send response back
header('Content-Type: application/json');
echo(json_encode($response));

?>