<?php
    $servername = "localhost";
    $username = "root";    # MySQL ID
    $password = "0321";    # MySQL Password
    $dbname = "bookbb";    # Database Name

    $conn = new mysqli($servername, $username, $password, $dbname);

    mysqlil_set_charset($conn, "utf8");

    if ($conn->connect_error) {
        die("Connection Failed : ", $conn->connect_error);
    }


    
