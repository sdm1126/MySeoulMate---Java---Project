document.addEventListener('DOMContentLoaded', () => {    
    // 1. 카드 번호
    // 1-1. 입력 시 처리 함수
    $("#cc_number").keyup (function () {
        // 숫자 이외 내용 입력 시 공백으로 변경
        this.value = this.value.replace(/[^\d\ ]/g, "");
    })

    // 1-2. 입력 종료 시 처리 함수
    $("#cc_number").change (function () {
        let strLength = $.trim($("#cc_number").val()).length;
        if(strLength < 13){
            alert("카드번호가 잘못 되었습니다.");
            $("#cc_number").focus();
        }  
    })

    // 2. 특별 요청사항
    // 2-1. 공백 처리 함수(reservation_insert.php의 strlen 체크 회피 목적)
    $("#submit").click (function () {
        let strLength = $.trim($("#special_request").val()).length;
        if(strLength === 0) {
            $("#special_request").val(" ");
        }
    })
})