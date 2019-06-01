<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js" ></script>

<script type="text/javascript">
var IMP = window.IMP; // 생략가능
IMP.init('imp60392328'); // 'iamport' 대신 부여받은 "가맹점 식별코드"를 사용

var register_id = '<?php echo $_POST['register_id']; ?>';
var book_name = '<?php echo $_POST['book_name']; ?>';
var book_price = '<?php echo $_POST['book_price']; ?>';
var phone_num = '<?php echo $_POST['phone_num']; ?>';

console.log(register_id);
console.log(book_name);
console.log(book_price);
console.log(phone_num);

/* 중략 */

//onclick, onload 등 원하는 이벤트에 호출합니다
IMP.request_pay({
    pg : 'html5_inicis', // version 1.1.0부터 지원.
    pay_method : 'card',
    merchant_uid : register_id, 
    //'merchant_' + new Date().getTime(),
    name : book_name,
    amount : book_price,
    buyer_tel : phone_num,
    m_redirect_url : 'https://www.bookboxbook.duckdns.org/payments/complete',
    app_scheme : 'iamportapp'
}, function(rsp) { //callback
    if ( rsp.success ) {
        var msg = '결제가 완료되었습니다.';
        msg += '고유ID : ' + rsp.imp_uid;
        msg += '상점 거래ID : ' + rsp.merchant_uid;
        msg += '결제 금액 : ' + rsp.paid_amount;
	msg += '카드 승인번호 : ' + rsp.apply_num;

	jQuery.ajax({
            url: "https://www.bookboxbook.duckdns.org/payments/complete", // 가맹점 서버
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: {
                imp_uid: rsp.imp_uid,
                merchant_uid: rsp.merchant_uid
            }
	})
	//	.done(function (data) {
	//	switch(data.status) {
        //        case: "vbankIssued":
        //            // 가상계좌 발급 시 로직
        //            break;
        //        case: "success":
        //            // 결제 성공 시 로직
        //            break;
       // })
    } else {
        var msg = '결제에 실패하였습니다.';
	msg += '에러내용 : ' + rsp.error_msg;
        alert("결제에 실패하였습니다. 에러 내용: " +  rsp.error_msg);

    }

    alert(msg);
});
</script>

