<?php
include "db.php";

// Temporary variable, used to store current query
$templine = '';

// Read in entire file
$lines = file("svlelectricals_test.sql");

$error = '';

// Loop through each line
foreach ($lines as $line){
    // Skip it if it's a comment
    if(substr($line, 0, 2) == '--' || $line == ''){
        continue;
    }
    
    // Add this line to the current segment
    $templine .= $line;
    
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';'){
        // Perform the query
        if(mysqli_query($connection,$templine)){
            $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $connection->error . '<br /><br />';
        }
        
        // Reset temp variable to empty
        $templine = '';
    }
}
return !empty($error)?$error:true;

?>