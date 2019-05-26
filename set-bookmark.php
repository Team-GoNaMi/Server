<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");


    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");


    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
		
		$user_id = $_POST["user_id"];
        $register_id = $_POST["register_id"];
		$state = $_POST["state"];
	
		if (empty($user_id)){
			$errMSG = "사용자 아이디가 없습니다.";
		}
		else if(empty($register_id)){
            $errMSG = "책 등록 번호가 없습니다.";
        }
        else if(empty($state)){
            $errMSG = "상태가 없습니다.";
        }

        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
            try{
                // SQL문을 실행하여 북 마크를 등록 및 해제합니다.
				
				echo $state;
				if ($state == "1") {	// 북마크 해제
					$stmt = $con->prepare("DELETE FROM book_mark WHERE book_register_id=:register_id AND member_id=:user_id");
					echo "Delete";
				}

				else if ($state == "2") {	// 북마크 등록
					$stmt = $con->prepare("INSERT INTO book_mark(book_register_id, member_id) VALUES(:register_id, :user_id)");
					echo "Insert";
				}

//                $stmt = $con->prepare("INSERT INTO member(member_id, password, name, phonenum, school) VALUES(:id, :hashed_pw, :name, :phonenum, :school)");
                $stmt->bindParam(":register_id", $register_id);
                $stmt->bindParam(":user_id", $user_id);
//				$stmt->execute();

                if($stmt->execute())
                {
                    $successMSG = " 북마크를 추가/삭제했습니다.";
					echo ": Success";
                }
                else
                {
                    $errMSG = "북마크 추가/삭제 에러";
					echo " - Failed";
                }

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
        }
       
    }

?>
