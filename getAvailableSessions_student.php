<?php

//include these to work with the database
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();
$hawkId = $_SESSION['hawkId'];

$tablename = 'sessions';

//query to obtain just the sessions with readable dates
$query = "SELECT sessions.id, users.first_name, users.last_name, course_id,
          DATE_FORMAT(sessions.slot, '%M %D, %Y %H:%i %p') as slot_date
          FROM $tablename JOIN users ON hawk_id='$hawkId'
          WHERE course_id=(SELECT course_id FROM students WHERE hawk_id='$hawkId')
          AND available=TRUE
          ORDER BY slot ASC;";

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
