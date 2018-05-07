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
    $hashedpass = crypt($password, getsalt());

   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

    // check for required fields
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
    
$queryUsert = "INSERT INTO users(hawk_id, first_name, last_name, hashedpass, student, tutor, administrator, professor) VALUES ($hawkId, $firstName, $lastName, $hashedpass, TRUE, FALSE, FALSE, FALSE);"
$queryStudentt = "INSERT INTO students(hawk_id, course_id) VALUES ($hawkId, $courseId);"

queryDB($queryUsert, $db);
queryDB($queryStudentt, $db);
	
$status = 'success';
$response['status'] = $status;

header('Content-Type: application/json');
echo(json_encode($response));

?>
