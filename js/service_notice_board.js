document.addEventListener('DOMContentLoaded', () => {
    const search = document.querySelector('#search'); // 검색 버튼(submit)
    const root = document.querySelector('#root'); // 검색 기준(select)
    const nameId = document.querySelector('#nameId'); // 검색어(text)
    search.addEventListener('click', () => {
        if (nameId.value.trim.length === 0) {
            location.replace('./service_notice_board.php?page=1');
        } else {
            location.replace('./service_notice_board.php?page=1&search=' + root.value + '&nameId=' + nameId.value);
        }
    })
})