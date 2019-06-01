<?php 
	
	function send_notification ($token, $data)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		//어떤 형태의 data/notification payload를 사용할것인지에 따라 폰에서 알림의 방식이 달라 질 수 있다.
		$msg = array(
			'title'	=> $data["title"],
			'body' 	=> $data["body"]
          	);

		//data payload로 보내서 앱이 백그라운드이든 포그라운드이든 무조건 알림이 떠도록 하자.
		$fields = array(
			'to'=> $token,
			'priority' => "high",
			'notification'	=> $msg
			);

		//구글키는 config.php에 저장되어 있다.
		$headers = array(
			'Authorization:key =' . "AAAA_CrZ5WA:APA91bFUCNJ24fUk8x6nYkzQ2NWt7lnp-boSL8n4onQJOPq1EbrpAkCFI4Ptl0sOg0kS8szjT74Iy_zq55V-K-NXjJccjGl2dt5Qmu_7UVdiwnFUQ07Ep8Osp_iqPXpVsVcklsfPCfrm",
			'Content-Type: application/json'
			);

	   	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		$result = curl_exec($ch);           
		
//		var_dump($result);
//		print_r(curl_getinfo($ch));



		if (!$result) {
			die('Curl failed: ' . curl_error($ch));
       		}
       		curl_close($ch);
       		return $result;
	}
?>



