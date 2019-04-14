<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

//        $id=$_POST["id"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        
        $stmt = $con->prepare("SELECT * FROM book");
        $stmt->execute();
	
	if ($stmt->rowCount() > 0) {
	    $book_names = array();
	    
	    while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		
		array_push($book_names, array("name"=>$name));
	    }

                           
	    header("Content-Type: application/jason; charset-utf8");
            echo json_encode(array("book_names"=>$book_names), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

    }

?>       
