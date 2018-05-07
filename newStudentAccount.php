<?php
    include_once('config.php');
    include_once('dbutils.php');

    // get data from form
    $data = json_decode(file_get_contents('php://input'), true);
    $hawkId = $data['hawkId'];
    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
	$password = $data['password'];
    $courseId = $data['courseId'];

   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

    // check for required fields
    $isComplete = true;
    $errorMessage = "";

    // check if hawkId meets criteria
    if (!isset($hawkId) || (strlen($hawkId) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a hawkId with at least two characters. ";
    } else {
        $hawkId = makeStringSafe($db, $hawkId);
    }
    //check if first name meets criteria
    if (!isset($firstName) || (strlen($firstName) < 1)) {
        $isComplete = false;
        $errorMessage .= "Please enter a password with at least six characters. ";
    }
    //check if last name meets criteria
    if (!isset($lastName) || (strlen($lastName) < 1)) {
        $isComplete = false;
        $errorMessage .= "Please enter a password with at least six characters. ";
    }
    //check if password meets criteria
    if (!isset($password) || (strlen($password) < 6)) {
        $isComplete = false;
        $errorMessage .= "Please enter a password with at least six characters. ";
    }
    //check if course_id meets criteria
    if (!isset($courseId) || (strlen($courseId) < 6)) {
        $isComplete = false;
        $errorMessage .= "Please enter a password with at least six characters. ";
    }

// one of the things we want to send back is the data that his php file received
ob_start();
// uncomment when testing
//var_dump($data);
$postdump = ob_get_clean();

// set up our response array
$response = array();
$response['status'] = 'error';
$response['message'] = $errorMessage . $postdump;
header('Content-Type: application/json');
echo(json_encode($response));

?>
