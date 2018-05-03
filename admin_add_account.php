<?php

//get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

//get data from the angular controller
//decode the json object
$data = json_decode(file_get_contents('php://input'), true);


//get each piece of data
//name matches the name attribute in the form
$hawkid = $data['hawk_id'];
$first_name = $data['first_name'];
$last_name = $data['last_name'];


//set up variables to handle errors
//is complete will be false if we find any problems when checking on the data

$isComplete = true; //is the form complete?

//error message we’ll send back to angular if we run into any problems
$errorMessage = "";

//check if hawkid meets criteria
if (!isset($hawkid) || (strlen($hawkid) < 4 )) {
	$isComplete = false;
	$errorMessage .= "Please enter a hawk_id with at least 4 characters.";

} else {
	$hawkid = makeStringSafe($db, $hawkid);

}

//check if we already have a user with the same hawk_id as the one we are processing.
if ($isComplete) {
	//set up a query to check if this user is in the database already
	$query = "SELECT hawk_id FROM users WHERE hawk_id='$hawkid'"; 

	//we need to run the query. 
	$result = queryDB($query, $db);
	
	//check on the number of records returned
	if (nTuples($result) > 0) {
		//if we get at least 1 record back, it means the player is already in the database, so we have a duplicate.
		$isComplete = false;
		$errorMessage .= "The person with the hawk_id $hawkid is already in the database." ;


	}

}

//if we got this far and $isComplete is true, it means we should add the user to the DB
if ($isComplete) {
	//we willl setup the insert statement to add this new record to the database
	$insertquery = "INSERT INTO users (hawk_id, first_name, last_name) VALUES ('$hawkid', '$first_name', '$last_name')";

	//run the insert statement
	queryDB($insertquery, $db);

	//get the id of the player we just entered
	$hawkid = mysqli_insert_id($db);

	//send a response back to angular
	$response = array();
	$response['status'] = 'success';
	$response['hawk_id'] = $hawkid;
	header('Content-Type: application/json');
	echo(json_encode($response));


} else {
	//there’s been an error. We need to report it to the angular controller.

	//one of the things we want to send back is the data that this php file received
	ob_start();
	var_dump($data);
	$postdump = ob_get_clean();

	//set up our response array
	$response = array();
	$response['status'] = 'error';
	$response['message'] = $errorMessage . $postdump; 
	header('Content-Type: application/json');
	echo(json_encode($response));



}
?>