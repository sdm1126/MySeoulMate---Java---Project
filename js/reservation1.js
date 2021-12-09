document.addEventListener('DOMContentLoaded', () => {
    // 기능 관련
    // 기능 1. Datepicker
    // 1-1. 달력 한글화
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });

    // 1-2. 달력 세팅
    $(function() {
        // 최소 체크인 일자(오늘), 최소 체크아웃 일자(내일) 초기화
        $("#check_in").datepicker({minDate: 0});
        $("#check_out").datepicker({minDate: 1});

        // 키보드에 의한 입력 막기
        $("#check_in").attr("readonly", true);
        $("#check_out").attr("readonly", true);
    });

    // 1-3. 메인 화면에서 예약 화면으로 이동하여 체크인 포커스 시, 최대 체크인 일자 설정
    $("#check_in").on("focus", function () {
        let number_check_out = $("#check_out").datepicker("getDate"); // number형
        let date_max_check_in = new Date((number_check_out - (24 * 60 * 60 * 1000))); // Date형
        let max_check_in = date_max_check_in.getFullYear() + "-" + (date_max_check_in.getMonth() + 1) + "-" + date_max_check_in.getDate(); // string형
        // $('#check_in').datepicker("option", "endDate", max_check_in);
        $("#check_in").datepicker("option", "maxDate", max_check_in);
    }); 

    // 1-4. 체크인 선택 시, 최소 체크아웃 일자 설정
    $("#check_in").on("change", function () {
        let number_check_in = $(this).datepicker("getDate"); // number형
        let date_min_check_out = new Date((number_check_in + (24 * 60 * 60 * 1000))); // Date형
        let min_check_out = date_min_check_out.getFullYear() + "-" + (date_min_check_out.getMonth() + 1) + "-" + date_min_check_out.getDate(); // string형

        $("#check_out").datepicker("option", "startDate", min_check_out);
        // $('#check_out').datepicker("option", "minDate", min_check_out_next);
    });
    
    // 1-5. 체크아웃 선택 시, 최대 체크인 일자 설정
    $("#check_out").on("change", function () {
        let number_check_out = $(this).datepicker("getDate"); // number형
        let date_max_check_in = new Date((number_check_out - (24 * 60 * 60 * 1000))); // Date형
        let max_check_in = date_max_check_in.getFullYear() + "-" + (date_max_check_in.getMonth() + 1) + "-" + date_max_check_in.getDate(); // string형
        // $('#check_in').datepicker("option", "endDate", max_check_in);
        $("#check_in").datepicker("option", "maxDate", max_check_in);
    });

    // 함수 관련
    // 함수 1. 상품 검색 함수    
    function search_deal() {
        let date_check_in = new Date($("#check_in").datepicker("getDate")); // Date형
        if(date_check_in.getMonth() + 1 < 10 && date_check_in.getDate() < 10) {
            check_in = date_check_in.getFullYear() + "-0" + (date_check_in.getMonth() + 1) + "-0" + date_check_in.getDate(); // string형    
        } else if(date_check_in.getMonth() + 1 < 10) {
            check_in = date_check_in.getFullYear() + "-0" + (date_check_in.getMonth() + 1) + "-" + date_check_in.getDate(); // string형    
        } else if(date_check_in.getDate() < 10) {
            check_in = date_check_in.getFullYear() + "-" + (date_check_in.getMonth() + 1) + "-0" + date_check_in.getDate(); // string형
        } else {
            check_in = date_check_in.getFullYear() + "-" + (date_check_in.getMonth() + 1) + "-" + date_check_in.getDate(); // string형
        }

        let date_check_out = new Date($("#check_out").datepicker("getDate")); // Date형
        if(date_check_out.getMonth() + 1 < 10 && date_check_out.getDate() < 10) {
            check_out = date_check_out.getFullYear() + "-0" + (date_check_out.getMonth() + 1) + "-0" + date_check_out.getDate(); // string형    
        } else if(date_check_out.getMonth() + 1 < 10) {
            check_out = date_check_out.getFullYear() + "-0" + (date_check_out.getMonth() + 1) + "-" + date_check_out.getDate(); // string형    
        } else if(date_check_out.getDate() < 10) {
            check_out = date_check_out.getFullYear() + "-" + (date_check_out.getMonth() + 1) + "-0" + date_check_out.getDate(); // string형
        } else {
            check_out = date_check_out.getFullYear() + "-" + (date_check_out.getMonth() + 1) + "-" + date_check_out.getDate(); // string형
        }

        // Check if check-in date and check-out date are selected
        if(check_in === "1970-01-01" || check_out === "1970-01-01") {
            document.querySelector(".section3-3-sub").innerHTML = "<h4 style=\"color: red\">체크인, 체크아웃 날짜를 모두 선택해주세요.</h4>";
            return;
        }

        // Create an XMLHttpRequest object
        let xhttp = new XMLHttpRequest();

        // Create the function to be executed when the server response is ready
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                document.querySelector(".section3-3-sub").innerHTML = this.responseText;
            }
        };

        // Notice that a parameter (q) is added to the URL (with the content of the dropdown list)
        xhttp.open("GET", "reservation1_search_deal.php?q="+check_in+"_"+check_out, true);
        
        // Send the request off to a file on the server
        xhttp.send();
    }

    // 1-1 체크인 일자 선택 시,
    $("#check_in").on("focus", function() {
        search_deal();
    });

    $("#check_in").on("change", function() {
        search_deal();
    });

    // 1-2. 체크아웃 일자 선택 시,
    $("#check_out").on("change", function() {
        search_deal();
    });

    // 함수 2. 인원 수 제한 함수
    let check_capacity = () => {
        // 4인 초과 시, 
        if(parseInt($("#adult").val()) + parseInt($("#child").val()) > 4) {
            alert('총 4명까지만 투숙 가능합니다. 인원을 다시 선택해주세요.');
            // localStorage에 저장된 인원 수 세팅
            $("#adult").val(localStorage.getItem("adult"));
            $("#child").val(localStorage.getItem("child"));

        // 1인 미만 시, 
        } else if (parseInt($("#adult").val()) + parseInt($("#child").val()) < 1) {
            alert('최소 1명 이상 선택 부탁드립니다. 인원을 다시 선택해주세요.');
            // localStorage에 저장된 인원 수 세팅
            $("#adult").val(localStorage.getItem("adult"));
            $("#child").val(localStorage.getItem("child"));

        // 정상 인원 선택 시,
        } else {
            // localStorage에 인원 수 데이터 저장
            localStorage.clear();
            localStorage.setItem("adult", $("#adult").val());
            localStorage.setItem("child", $("#child").val());
        }
    }

    // 함수 3. 공백 여부 체크 함수
    let check_blank = () => {
        // 성인 인원 수 체크
        if($("#adult").val() === "") {
            $("#adult").val(0);
        }

        // 소아 인원 수 체크
        if($("#child").val() === "") {
            $("#child").val(0);
        }

        // 성인 조식 체크
        if($("#adult_breakfast").val() === "") {
            $("#adult_breakfast").val(0);
        }

        // 소아 조식 체크
        if($("#child_breakfast").val() === "") {
            $("#child_breakfast").val(0);
        }
    }
    
    // 2-1, 3-1. 성인 인원 수 변경 시,
    $("#adult").on("change", function() {
        check_capacity();
        check_blank();
    });

    // 2-2, 3-2. 소아 인원 수 변경 시,
    $("#child").on("change", function() {
        check_capacity();
        check_blank();
    });

    // 2-3, 3-3. 성인 조식 변경 시,
    $("#adult_breakfast").on("change", function() {
        check_capacity();
        check_blank();
    });

    // 2-4, 3-4. 소아 조식 변경 시,
    $("#child_breakfast").on("change", function() {
        check_capacity();
        check_blank();
    });

    // 함수 4. 요금 검색 함수
    function search_tariff() {
        // 체크인, 체크아웃 날짜
        let date_check_in = new Date($("#check_in").datepicker("getDate")); // Date형
        if(date_check_in.getMonth() + 1 < 10 && date_check_in.getDate() < 10) {
            check_in = date_check_in.getFullYear() + "-0" + (date_check_in.getMonth() + 1) + "-0" + date_check_in.getDate(); // string형    
        } else if(date_check_in.getMonth() + 1 < 10) {
            check_in = date_check_in.getFullYear() + "-0" + (date_check_in.getMonth() + 1) + "-" + date_check_in.getDate(); // string형    
        } else if(date_check_in.getDate() < 10) {
            check_in = date_check_in.getFullYear() + "-" + (date_check_in.getMonth() + 1) + "-0" + date_check_in.getDate(); // string형
        } else {
            check_in = date_check_in.getFullYear() + "-" + (date_check_in.getMonth() + 1) + "-" + date_check_in.getDate(); // string형
        }

        let date_check_out = new Date($("#check_out").datepicker("getDate")); // Date형
        if(date_check_out.getMonth() + 1 < 10 && date_check_out.getDate() < 10) {
            check_out = date_check_out.getFullYear() + "-0" + (date_check_out.getMonth() + 1) + "-0" + date_check_out.getDate(); // string형    
        } else if(date_check_out.getMonth() + 1 < 10) {
            check_out = date_check_out.getFullYear() + "-0" + (date_check_out.getMonth() + 1) + "-" + date_check_out.getDate(); // string형    
        } else if(date_check_out.getDate() < 10) {
            check_out = date_check_out.getFullYear() + "-" + (date_check_out.getMonth() + 1) + "-0" + date_check_out.getDate(); // string형
        } else {
            check_out = date_check_out.getFullYear() + "-" + (date_check_out.getMonth() + 1) + "-" + date_check_out.getDate(); // string형
        }

        // Check if check-in date and check-out date are selected
        if(check_in === "1970-01-01" || check_out === "1970-01-01") {
            document.querySelector(".section3-3-sub").innerHTML = "<h2 style=\"color: red\">체크인, 체크아웃 날짜를 모두 선택해주세요.</h2>";
            return;
        }

        // 상품명, 타입
        let deal_info = $('input[class=room_type]:checked').val();   
        let array_deal_info = deal_info.split('_');
        let deal_name = array_deal_info[0];
        let room_type = array_deal_info[1];

        // Check if deal and type are selected
        if(room_type === undefined) {
            document.querySelector(".section4").innerHTML = "<h4 style=\"color: red\">상품, 타입을 선택해주세요.</h2>";
            return;
        }

        // 조식 인원
        let adult_breakfast = $('#adult_breakfast').val();
        let child_breakfast = $('#child_breakfast').val();

        // Create an XMLHttpRequest object
        let xhttp = new XMLHttpRequest();

        // Create the function to be executed when the server response is ready
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                document.querySelector(".section4").innerHTML = this.responseText;
            }
        };

        // Notice that a parameter (q) is added to the URL (with the content of the dropdown list)
        xhttp.open("GET", "reservation1_search_tariff.php?q="+check_in+"_"+check_out+"_"+adult_breakfast+"_"+child_breakfast+"_"+deal_name+"_"+room_type, true);
        
        // Send the request off to a file on the server
        xhttp.send();
    }

    // 4-1. 체크인 일자 변경 시
    $("#check_in").on("change", function() {
        if(check_in === "1970-01-01" && check_out === "1970-01-01") {
            search_tariff();
        } else {
            document.querySelector(".section4").innerHTML = "<h4 style=\"color: red\">상품, 타입을 선택해주세요.</h4>";
        }
    });

    // 4-2. 체크아웃 일자 변경 시
    $("#check_out").on("change", function() {
        if(check_in === "1970-01-01" && check_out === "1970-01-01") {
            search_tariff();
        } else {
            document.querySelector(".section4").innerHTML = "<h4 style=\"color: red\">상품, 타입을 선택해주세요.</h2>";
        }
    });

    // 4-3. 성인 조식 변경 시
    $("#adult_breakfast").on("change", function() {
        search_tariff();
    });

    // 4-4. 소아 조식 변경 시
    $("#child_breakfast").on("change", function() {
        search_tariff();
    });

    // 4-5. 객실 타입 변경 시(Ajax 통신에 의해 동적으로 추가된 태그에 이벤트를 적용하는 경우)
    $(document).on("change", ".room_type", function() {
        search_tariff();
    });
})

// 메인 화면에서 예약 화면으로 이동 시 체크인 포커스 함수
function focus_check_in() {
    $("#check_in").focus();
}