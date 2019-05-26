<?php
	echo "connected ";
	$test = $_POST["test"];

	echo $test;

//	$conn = mysql_connect("localhost", "gonami", "gonami");

	echo " gg";
	
	if (!$conn) {
		die("Could not connect mysql : " . mysql_error());
		echo "Could not connect mysql : " . mysql_error();
	}

	echo "db_connected";


/*		
	if(isset($_POST["test"])) {		

		$test = $_POST["test"];
		$conn = mysql_connect("localhost", "gonami", "gonami");

		if (!$conn) {
			die("could not connect : " . mysql_error());
		}

		mysql_select_db("bookboxbook", $conn);
    		$query = "SELECT * FROM member";

    		$re = mysql_query($query, $conn);
//		setcookie("test", "$test", time()+5000);

		$result_data = array();
		while($sql_result = mysql_fetch_array($re)) {
			$record = array();

			$record["member_id"] = $sql_result[0];
			$record["name"] = $sql_result["name"];
			$record["phonenum"] = $sql_result[3];

			array_push($result_data, $record);
		}
		
		$json = json_encode(array("member"=>$result_data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
                echo $json;
		}
 */	
?>
