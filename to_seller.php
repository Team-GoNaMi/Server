<!-- HTML -->
<button onclick="cancelPay()">deposit</button>

<script
	src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script><!-- jQuery CDN --->
<script type="text/javascript">
    	var owner = '<?php echo $_POST['owner']; ?>';
    	var bank = '<?php echo $_POST['bank']; ?>';
    	var account_num = '<?php echo $_POST['account_num']; ?>';

    	console.log(owner);
    	console.log(bank);
	console.log(account_num);

	function cancelPay() {

        	jQuery.ajax({
	    	    "url": "https://bookboxbook.duckdns.org/payment.php",
            	    "type": "POST",
            	    "contentType": "application/json",
            	    "data": JSON.stringify({
              	 	"merchant_uid": "20190531004434-1", // 주문번호
              		"cancel_request_amount": 0, // 환불금액
              		"reason": "deposit", // 환불사유
              		"refund_holder": "owner", // [가상계좌 환불시 필수입력] 환불 가상계좌 예금주
              		"refund_bank": "88", // [가상계좌 환불시 필수입력] 환불 가상계좌 은행코드(ex. KG이니시스의 경우 신한은행은 88번)
              		"refund_account": "account_num", // [가상계좌 환불시 필수입력] 환불 가상계좌 번호
            	    }),
            	    "dataType": "json"
          	}).done(function(result) { // 환불 성공시 로직 
            		alert("deposit succes");
          	}).fail(function(error) { // 환불 실패시 로직
            		alert("deposit fail");
          	});
    	}
</script>


