<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("../dbcon.php");
    error_log("TST", 0);


    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $register_id = $_POST["register_id"];
	$buyer_id = $_POST["buyer_id"];

	$today = date("Y-m-d");

//	echo $register_id;
//	echo $today;

	try {
            $stmt = $con->prepare("SELECT box_id, date FROM trade NATURAL JOIN reserve_bb WHERE book_register_id=:register_id AND buyer_id=:buyer_id AND date=:today LIMIT 1");
	    $stmt->bindParam(":register_id", $register_id);
	    $stmt->bindParam(":buyer_id", $buyer_id);
	    $stmt->bindParam(":today", $today);
	    $stmt->execute();

	    $box_row = $stmt->fetch(PDO::FETCH_ASSOC);

	    $result = array();
	    $result["success"] = false;

	    if ($stmt->rowCount() > 0) {
                $result["success"] = true;
		$result["box_id"] = $box_row["box_id"];
		$result["date"] = $box_row["date"];
            }
  

            $json = json_encode($result, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            echo $json;
  
	}catch (PDOException $e) {
            die("Database error : " .$e->getMessage());
        }
 
    } 
?> 
