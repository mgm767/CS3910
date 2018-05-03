<?php
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);
$tableName = 'users';

// Receive data from client
$data = json_decode(file_get_contents('php://input'), true);

$isComplete = true;
$errorMessage = '';
$response = array();
$status = '';

$first_name = $data['first_name'];
$last_name = $data['last_name'];
// $student = $data['student'];
// $professor = $data['professor'];
// $tutor = $data['tutor'];
// $administrator = $data['administrator'];
$hawk_id = $data['hawk_id'];
$original_hawk_id = $data['original_hawk_id'];


//
// Validation
//

if (!isset($original_hawk_id)) {
	$isComplete = false;
	$status = 'error';
	$errorMessage .= "Must include ID of user to be deleted: $original_hawk_id";
}

if (!isset($first_name)) {
	$isComplete = false;
	$errorMessage .= 'First name must be at least 1 character long.';
} else {
	$first_name = makeStringSafe($db, $first_name);
}

if (!isset($last_name)) {
	$isComplete = false;
	$errorMessage .= 'Last name must be at least 1 character long.';
} else {
	$last_name = makeStringSafe($db, $last_name);
}

// if (!isset($student)) {
// 	$isComplete = false;
// 	$errorMessage .= 'Student must be yes or no';
// } else {
// 	$student = makeStringSafe($db, $student);
// }

if($isComplete) {
	$query = "SELECT first_name, last_name FROM $tableName WHERE hawk_id='$original_hawk_id';";
	$result = queryDB($query, $db);
	
	if(nTuples(result) === 0) {
		$status = 'error';
		$errorMessage .= "Hawk_id $original_hawk_id does not match any record in the 'users' table";
	}
}

// Check if this user already exists but with a different hawk_id
// if ($isComplete) {
// 	$query = "SELECT first_name, last_name FROM users WHERE first_name='$first_name' AND last_name='$last_name' AND student='$student' AND hawk_id<>$hawk_id;";
// 	$result = queryDB($query, $db);
	
// 	if (nTuples($result)) {
// 		$isComplete = false;
// 		$errorMessage .= "User called $first_name $last_name already exists.";
// 	}
// }

if ($isComplete) {
	$query = "UPDATE $tableName SET first_name='$first_name', last_name='$last_name'  WHERE hawk_id='$original_hawk_id';";  // student='$student', tutor='$tutor'";
	queryDB($query, $db);
	
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