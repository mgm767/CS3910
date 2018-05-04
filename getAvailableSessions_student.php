<?php

//include these to work with the database
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();
$hawkId = $_SESSION['hawkId'];

//query to obtain just the available_sessions
$query = "SELECT * from available_sessions WHERE available_sessions.course_id=(SELECT course_id FROM students WHERE hawk_id='$hawkId');";

//query to database
$result = queryDB($query, $db);

$sessions = array();

$i = 0;

while ($currentSession = nextTuple($result)) {
        $sessions[$i] = $currentSession;
        $i++;
}

//make JSON object
$response = array();
$response['status'] = 'success';
$response['value']['sessions'] = $sessions;
header('Content-Type: application/json');
echo(json_encode($response));

?>