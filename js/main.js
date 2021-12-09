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

    // 1-3. 체크인 선택 시, 최소 체크아웃 일자 설정
    $("#check_in").on("change", function () {
        let number_check_in = $(this).datepicker("getDate"); // number형
        let date_min_check_out = new Date((number_check_in + (24 * 60 * 60 * 1000))); // Date형
        let min_check_out = date_min_check_out.getFullYear() + "-" + (date_min_check_out.getMonth() + 1) + "-" + date_min_check_out.getDate(); // string형
        $("#check_out").datepicker("option", "startDate", min_check_out);
        // $('#check_out').datepicker("option", "minDate", min_check_out);
    });
    
    // 1-4. 체크아웃 선택 시, 최대 체크인 일자 설정
    $("#check_out").on("change", function () {
        let number_check_out = $(this).datepicker("getDate"); // number형
        let date_max_check_in = new Date((number_check_out - (24 * 60 * 60 * 1000))); // Date형
        let max_check_in = date_max_check_in.getFullYear() + "-" + (date_max_check_in.getMonth() + 1) + "-" + date_max_check_in.getDate(); // string형
        // $('#check_in').datepicker("option", "endDate", max_check_in);
        $("#check_in").datepicker("option", "maxDate", max_check_in);
    });

    function check_blank() {
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
            document.querySelector(".section2").innerHTML = "<h2 style=\"color: red\">체크인, 체크아웃을 모두 선택해주세요.</h2>";
            location.reload();
            return false;
        } else {
            $("#form").submit();
        }
    }

    // 예약 버튼 클릭 시,
    $("#button").on("click", function() {
        check_blank();
    });
})

function start_slide() {
    // 1. 변수 지정
    let section1 = document.querySelector('.section1');
    let section1_slides = document.querySelector('.section1_slides');
    let slides = document.querySelectorAll('.section1_slides a'); // querySelectorAll(): 배열로 가져옴
    let indicators = document.querySelectorAll('.section1_indicator a'); // querySelectorAll(): 배열로 가져옴
    let prev = document.querySelector('.prev');
    let next = document.querySelector('.next');

    let slidesCount = slides.length;
    let currentIndex = 0; // 이미지 화면 위치 저장(0 → 1번 이미지, 1 → 2번 이미지, ...)
    let timer = 0; // 자동 타이머 변수

    // 2. 우선, 이미지를 가로 방향으로 절대 배치함
    for(let i = 0; i < slidesCount; i++) {
        let newLeft = i * 100 + '%'; // left값을 변경하여 style로 적용
        slides[i].style.left = newLeft;
    }

    // 3. 슬라이드 함수 생성
    function executeSlide(index) {
        currentIndex = index;

         // 0% → 1번 이미지, -100%, → 2번 이미지, ...
         let newLeft = currentIndex * -100 + '%';
         section1_slides.style.left = newLeft;
         section1_slides.classList.add('animated');
    
        // currentIndex에 따른 Indicator 적용
        for(let i=0; i < indicators.length; i++) {
            // class = "activated" 전체 삭제
            indicators[i].classList.remove('activated');

            // currentIndex에 class = "activated" 추가
            indicators[currentIndex].className = "activated";
        }
    }

    // 3-1. 이전으로 가기 버튼 핸들러 생성
    prev.addEventListener('click', function(e) {
        e.preventDefault(); // anchor의 기본 기능 삭제(이미지 변경 기능만 적용)
        
        // 인덱스가 0이 아닐 경우, 정상적으로 -1
        if(currentIndex !== 0) {
            currentIndex -= 1;
        // 인덱스가 0일 경우, 가장 마지막으로
        } else {
            currentIndex = slidesCount - 1;
        }

        executeSlide(currentIndex);
    })

    // 3-2. 다음으로 가기 버튼 핸들러 생성
    next.addEventListener('click', function(e) {
        e.preventDefault(); // anchor의 기본 기능 삭제(이미지 변경 기능만 적용)
        
        // 인덱스가 마지막이 아닐 경우, 정상적으로 +1
        if(currentIndex !== slidesCount - 1) {
            currentIndex += 1;
        // 인덱스가 마지막일 경우, 가장 처음으로
        } else {
            currentIndex = 0;
        }

        executeSlide(currentIndex);
    })

    // 3-3. Indicator에 의한 이동 핸들러 생성
    for(let i = 0; i < indicators.length; i++) {
        indicators[i].addEventListener('click', function(e) {
            e.preventDefault();
            executeSlide(i);
        })
    }

    // 4. 타이머 함수 생성
    function executeTimer() {
        timer = setInterval(function() {
            // % slidesCount = nextIndex가 최대 페이지 인덱스를 넘지 않게 함
            let nextIndex = (currentIndex + 1) % slidesCount;
            executeSlide(nextIndex);
        }, 2000);
    }

    // 4-1. 마우스가 내려가면 타이머 실행
    section1.addEventListener('mouseleave', function() {
        executeTimer();
    })

    // 4-2. 마우스가 올라가면 타이머 중단
    section1.addEventListener('mouseenter', function() {
        clearInterval(timer);
    })
}