<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");


    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");


    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

	$isbn=$_POST["isbn"];
	$book_name=$_POST["book_name"];
	$author=$_POST["author"];
	$publisher=$_POST["publisher"];
	$original_price=$_POST["original_price"];
	$publish_date=$_POST["publish_date"];

	$register_id=$_POST["register_id"];
	$seller_id=$_POST["seller_id"];
	$school=$_POST["school"];
	$selling_price=$_POST["selling_price"];
	$book_image=$_POST["book_image"];
	$underline=$_POST["underline"];
	$writing=$_POST['writing'];
	$cover=$_POST["cover"];
	$damage_page=$_POST["damage_page"];
	$memo=$_POST["memo"];
	$buy_avail=$_POST["buy_avail"];



        if(empty($isbn)){
	    $errMSG = "ISBN을 입력해 주세요.";
	}
        else if(empty($register_id)){
            $errMSG = "책 등록번호를 입력해 주세요.";
        }
        else if(empty($seller_id)){
            $errMSG = "판매자 정보를 입력해 주세요.";
        }



        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
	    // ISBN 같은 거 있는 지 체크 후 Book Table에 삽입
	    $dupISBNcheck = $con->prepare("SELECT * FROM book WHERE ISBN=:isbn");
	    $dupISBNcheck->bindParam("isbn", $isbn);
	    $dupISBNcheck->execute();
	    
	    if ($dupISBNcheck->rowCount() == 0) { 
            	try{
            // SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다.

		    $stmt = $con->prepare("INSERT INTO book(ISBN, name, author, publisher, price, publish_date) VALUES(:isbn, :name, :author, :publisher, :price, :publish_date)");
		    $stmt->bindParam(":isbn", $isbn);
		    $stmt->bindParam(":name", $book_name);
		    $stmt->bindParam(":author", $author);
		    $stmt->bindParam(":publisher", $publisher);
		    $stmt->bindParam(":price", $original_price);
		    $stmt->bindParam(":publish_date", $publish_date);
		

            	    if($stmt->execute())
                    {
                    	$successMSG = "새로운 책을 추가했습니다.";
                    }
                    else
                    {
                        $errMSG = "책 추가 에러";
                    }

                } catch(PDOException $e) {
                    die("Database error: " . $e->getMessage()); 
                }    
	    }

	    // Register_book Table에 삽입
	    try {
		$stmt = $con->prepare("INSERT INTO register_book(book_register_id, ISBN, seller_id, price, memo, buy_avail, underline, writing, cover, damage_page) VALUES(:book_register_id, :ISBN, :seller_id, :price, :memo, :buy_avail, :underline, :writing, :cover, :damage_page)");
	 	$stmt->bindParam(":book_register_id", $register_id);
		$stmt->bindParam(":ISBN", $isbn);
		$stmt->bindParam(":seller_id", $seller_id);
		$stmt->bindParam(":price", $selling_price);
		$stmt->bindParam(":memo", $memo);
		$stmt->bindParam(":buy_avail", $buy_avail);
		$stmt->bindParam(":underline", $underline);
		$stmt->bindParam(":writing", $writing);
		$stmt->bindParam(":cover", $cover);
		$stmt->bindParam(":damage_page", $damage_page);

		if($stmt->execute()) {
		    $successMSG = "새로운 책을 등록하였습니다.";
		}
	 	else {
		    $errMSG = "책 등록 에러";
		}
	    } catch (PDOException $e) {
		die("Database error :" . $e->getMessage());
	    }
		
	    // TODO 책 사진 추가 및 학교 추가
 
	}       
    }

?>
