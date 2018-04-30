<?php

include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$tablename = "available_sessions";
// Ensure session will occur in the future
$query = "SELECT * FROM $tablename WHERE slot >= CURDATE();";

$result = queryDB($query, $db);

$sessions = array();

$i = 0;

while ($currentSession = nextTuple($result)) {
    $sessions[$i] = $currentSession;
    $i++;
}

$response = array();
$response['status'] = 'success';
$response['value']['sessions'] = $sessions;
header('Content-Type: application/json');
echo(json_encode($response));

?>