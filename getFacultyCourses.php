<?php

include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$query = "SELECT * FROM courses INNER JOIN professors on courses.course_id=professors.course_id where professors.hawk_id=;";

$result = queryDB($query, $db);

$courses = array();

$i = 0;

while ($currentCourse = nextTuple($result)) {
        $courses[$i] = $currentCourse;
        $i++;
}

$response = array();
$response['status'] = 'success';
$response['value']['courses'] = $courses;
header('Content-Type: application/json');
echo(json_encode($response));

?>