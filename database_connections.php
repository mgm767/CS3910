<?php

    //include necessary files
    include_once('config.php');
    include_once('dbutils.php');
    
    function parse_query($input) {
        $anArray = array ();
        $i = 0;
        while($row = nextTuple($input)){
            $anArray[$i] = $row;
            $i++; 
        }
        return $anArray;  
    }
    
    //database connection
    $db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);
    
    //query to get all current accounts for admin & professor
    $query_accounts = "SELECT * FROM users;";
    
    $result_accounts = queryDB($query_accounts, $db);
    
    $accounts_list = parse_query($result_accounts);
    
    $response = array();
    $response['status'] = 'success';
    
    
?>