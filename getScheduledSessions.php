<?php

//include these to work with the database
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start(); // getting hawkID of current user
$hawkId = $_SESSION['hawkId'];

//query to obtain just the scheduled sessions for this user
$query = "SELECT * FROM scheduled_sessions JOIN available_sessions on (scheduled_sessions.id=available_sessions.id) WHERE (scheduled_sessions.student_id='$hawkId');";
//$query = "SELECT available_sessions.course_id, available_sessions.slot, available_sessions.tutor_id , scheduled_sessions.student_id, scheduled_sessions.doc_id FROM available_sessions INNER JOIN scheduled_sessions on available_sessions.id = scheduled_sessions.id WHERE available_sessions.student_id = '$hawkId';";

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