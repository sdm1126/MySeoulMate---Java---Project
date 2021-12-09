document.addEventListener('DOMContentLoaded', () => {
    const search = document.querySelector('#search');
    const search_excel = document.querySelector('#search_excel');
    const root = document.querySelector('#root');
    const nameId = document.querySelector('#nameId');
    search.addEventListener('click', (event) => {
        if (nameId.value.trim() === '') {
            location.replace('./adminpage_inquiry_board.php?page=1');
        } else {
            location.replace('./adminpage_inquiry_board.php?page=1&search=' + root.value + '&nameId=' + nameId.value);
        }
    })

    // 현재 url값을 구해서 그 값을 기준으로 값을 넘겨줌
    const excel = function(){
        let url = new URL(window.location.href)
        let root_value = url.searchParams.get('search')
        let nameId_value = url.searchParams.get('nameId')
        
        if (nameId_value === null) {
            location.replace('./adminpage_excel.php?mode=inquiry');
        } else {
            location.replace('./adminpage_excel.php?mode=inquiry&search=' + root_value + '&nameId=' + nameId_value);
        }
    }
    search_excel.addEventListener('click', excel)
})

