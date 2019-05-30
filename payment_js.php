<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js" ></script>

<script type="text/javascript">
var IMP = window.IMP; // 생략가능
IMP.init('imp60392328'); // 'iamport' 대신 부여받은 "가맹점 식별코드"를 사용

//var book_name = "<?php echo $book_name; ?>";
/* 중략 */

//onclick, onload 등 원하는 이벤트에 호출합니다
IMP.request_pay({
    pg : 'inicis', // version 1.1.0부터 지원.
    pay_method : 'card',
    merchant_uid : 'merchant_' + new Date().getTime(),
    name : 'test',
    amount : 14000,
    buyer_tel : '010-1234-5678',
    m_redirect_url : 'https://www.bookboxbook.duckdns.org/payments/complete',
    app_scheme : 'iamportapp'
}, function(rsp) { //callback
    if ( rsp.success ) {
        var msg = '결제가 완료되었습니다.';
        msg += '고유ID : ' + rsp.imp_uid;
        msg += '상점 거래ID : ' + rsp.merchant_uid;
        msg += '결제 금액 : ' + rsp.paid_amount;
        msg += '카드 승인번호 : ' + rsp.apply_num;
    } else {
        var msg = '결제에 실패하였습니다.';
        msg += '에러내용 : ' + rsp.error_msg;
    }

    alert(msg);
});
</script>

