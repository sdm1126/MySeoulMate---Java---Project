document.addEventListener('DOMContentLoaded', () => {
    let select = document.querySelector("#select"); // 검색 기준
    let keyword = document.querySelector("#keyword"); // 검색어
    let submit = document.querySelector("#submit"); // 조회 버튼

    submit.addEventListener('click', () => {
        if (keyword.value.trim() === "") {
            location.replace('./mypage_reservation.php?');
        } else {
            location.replace('./mypage_reservation.php?select=' + select.value + '&keyword=' + keyword.value);
        }
    })
})