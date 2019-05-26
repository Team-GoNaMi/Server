<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("../dbcon.php");
    error_log("TST", 0);


    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $resgister_id = $_POST["register_id"];
	$role = $_POST["role"];


	try {
            $stmt = $con->prepare("SELECT box_id FROM register_book NATURAL JOIN reserve_bb WHERE book_register_id=:register_id AND seller_id=:seller_id AND date=:today LIMIT 1");
	    if ($role)
		    $stmt = $con->prepare("UPDATE trade SET state=3 WHERE book_register_id=:register_id");
	    else
		    $stmt = $con->prepare("UPDATE trade SET state=4 WHERE book_register_id=:register_id");

	    $stmt->bindParam(":register_id", $register_id);
	    $stmt->execute(); 

  
	}catch (PDOException $e) {
            die("Database error : " .$e->getMessage());
        }
 
    } 
?> 
