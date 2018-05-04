<?php


//we need to include these two gfiles in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();
$hawk_Id = $_SESSION['hawkId'];

//get data from the angular controller
//decode the json object
$data = json_decode(file_get_contents('php://input'), true);


//get each piece of data
// 'name' matches the name attribute in the form
$slot = $data['slotDate'];
$slot = $data['slotTime'];
$course_id = $data['course_id'];


//set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;

//error message we'll send back to angular if we run into any problems
$errorMessage = "";

// 
// Validation
//

//check is name meets criteria
if (!isset($slot) || (strlen($slot) < 2)) {
	$isComplete = false;
	$errorMessage .= "Please enter a date with at least 2 characters.";
} else {
	$slot = makeStringSafe($db,$slot);
}

if (!isset($course_id) || (strlen($course_id) < 0)) {
	$isComplete = false;
	$errorMessage .= "Please enter a course with at least 2 characters.";
} else {
	$course_id = makeStringSafe($db,$course_id);
}




//check if we already have a player with the same name, country, and club as the one we are processing
if ($isComplete) {
	//set up a query to check if this movie is in the database
	$query = "SELECT slot, slot FROM available_sessions WHERE slot='$slot' AND slot='$slot' AND course_id='$course_id' AND tutor_id = '$tutor_id';";
	
	
	//run the query
	$result = queryDB($query, $db);
	//check on the number of records returned
	if(nTuples($result) > 0) {
		// if we get at least one record back it means the movie is already in the database, so we have a duplicate
		$isComplete = false;
		$errorMessage .= "The session $slot, $slot, for the course $course_id, is taken, please select another time.";
		
	}
}

// if we got this far and $isComplete is true it means we should add the movie to the database
if ($isComplete) {
	// we will set up the insert statement to add this new record to the database
	$insertquery = "INSERT INTO available_sessions(slot, course_id, tutor_id) VALUES ('$slot', (SELECT course_id FROM courses WHERE course_id ='$course_id'), '$tutor_id');";
	
	//run the insert statement
	queryDB($insertquery, $db);
	
	// get the id for movie we just entered
	$movieid = mysqli_insert_id($db);
	
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
