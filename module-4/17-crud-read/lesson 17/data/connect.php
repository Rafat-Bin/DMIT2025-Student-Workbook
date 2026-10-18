<?php 

function db_connect(){


    $host = 'mysql';
    $username= 'student';
    $password = 'student';
    $database = 'dmit2025';

    $conn= mysqli_connect($host, $username, $password, $database);

    if(mysqli_connect_errno()){
        echo "Failed to connect to Mysql:".mysqli_connect_error();
        exit();

    }

    return $conn;

}

?>