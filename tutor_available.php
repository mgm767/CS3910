<?php

//we need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

//get data from the angular controller
//decode the json object
$data = json_decode(file_get_contents('php://input'), true);


//get each piece of data
$slot = $data['slot'];
$course_id = $data['course_id'];

// Get tutor's hawk_id and role from session info
session__start();
$tutor_id = $_SESSION['hawkId'];
$role = $_SESSION['autorizedRole'];

//set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;

//error message we'll send back to angular if we run into any problems
$errorMessage = "";

// 
// Validation
//

//check is time slot meets criteria
if (!isset($slot) || (strlen($slot) < 2)) {
	$isComplete = false;
	$errorMessage .= "Please enter a slot with at least 2 characters.";
} else {
	$slot = makeStringSafe($db,$slot);
}

if (!isset($course_id) || (strlen($course_id) < 2)) {
	$isComplete = false;
	$errorMessage .= "Please enter a course ID with at least 2 characters.";
} else {
	$course_id = makeStringSafe($db,$course_id);
}

// Ensure that a tutor is currently logged in
if ($role != 'tutor') {
	$isComplete = false;
	$errorMessage .= " Must be a tutor to add an available tutoring session.";
}


//check if we already have a session with the same time, course, and tutor
if ($isComplete) {
	//set up a query to check if this movie is in the database
	$query = "SELECT slot FROM available_sessions WHERE slot='$slot' AND course_id='$course_id' AND tutor_id='$tutor_id'";
	
	//run the query
	$result = queryDB($query, $db);
	//check on the number of records returned
	if(nTuples($result) > 0) {
		// if we get at least one record back it means the session is already in the database, so we have a duplicate
		$isComplete = false;
		$errorMessage .= "The session $slot, for course $course_id, with tutor $tutor_id, is already in the database.";
		
	}
}

// if we got this far and $isComplete is true it means we should add the session to the database
if ($isComplete) {
	// we will set up the insert statement to add this new record to the database
	$insertquery = "INSERT INTO available_sessions(slot, course_id, tutor_id) VALUES ('$slot', '$course_id', '$tutor_id')";
	
	//run the insert statement
	queryDB($insertquery, $db);
	
	// get the id for session we just entered
	$id = mysqli_insert_id($db);
	
	//send a response back to angular
	$response = array();
	$response['status'] = 'success';
	header('Content-Type: application/json');
	echo(json_encode($response));
} else {
	// there's been an error. We need to report it to the angular controller.
	
	// one of the things we want to send back is the data that this php file recieved
	ob_start();
	var_dump($data);
	$postdump = ob_get_clean();
	
	//set up response array
	$response = array();
	$response['status'] = 'error';
	$response['message'] = $errorMessage . $postdump;
	header('Content-Type: application/json');
	echo(json_encode($response));
}

?>