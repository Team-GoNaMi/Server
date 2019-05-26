<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("../dbcon.php");
    error_log("TST", 0);

    echo "connected ";


    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
	$test = $_POST["test"];
	echo $test;

	try {
	    echo " try";

	    $stmt = $con->prepare("SELECT * FROM member");
	    $stmt->execute();

	    $result = array();
	    if ($stmt->rowCount() > 0) {
		    
		while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)) {
			
		    $user = array();
		    $user["id"] = $userRow["member_id"];
		    $user["name"] = $userRow["name"];
		    $user["phonenum"] = $userRow["phonenum"];

		    array_push($result, $user);

		}
  
	    }

	    $json = json_encode(array("basic"=>$result), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
	    echo $json;
  
	}catch (PDOException $e) {
	    echo "error";
            die("Database error : " .$e.getMessage());
        }
 
    } 
?> 
