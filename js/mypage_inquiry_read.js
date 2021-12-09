document.addEventListener('DOMContentLoaded', function(){

    let btn_modify = document.querySelector('#btn_modify')
    let btn_delete = document.querySelector('#btn_delete')
    let title = document.querySelector('#title')
    let content = document.querySelector('#content')

    let comment_update = document.querySelectorAll('.comment_update')
    let comment_delete = document.querySelectorAll('.comment_delete')
    let comment_content = document.querySelectorAll('.comment_content')

    // 삭제 버튼 함수 (삭 제, 취 소 버튼 이벤트) 
    function delete_button_change(button) {
        if (button.value === "삭 제") {
            if (confirm('정말 삭제하시겠습니까?')) {
                button.type = "submit";
            }
        } else if (button.value === "취 소") {
            location.reload()
        }
    }

    // 게시글 수정 버튼 이벤트
    btn_modify.addEventListener('click', () => {
        if (btn_modify.value === "수 정") {
            btn_modify.value = "확 인"
            btn_delete.value = "취 소"
            // 읽기 전용 속성 해제
            title.readOnly = false
            content.readOnly = false
        } else if (btn_modify.value === "확 인") {
            btn_modify.type = "submit"
        }
    })

    // 게시글 삭제 버튼 이벤트
    btn_delete.addEventListener('click', () => {
        // 삭제 함수 호출
        delete_button_change(btn_delete)
    })

    // 댓글 수정 버튼 이벤트(모든 댓글에 적용)
    for (let i = 0; i < comment_update.length; i++) {
        comment_update[i].addEventListener('click', () => {
            if (comment_update[i].value === "수 정") {
                comment_update[i].value = "확 인"
                comment_delete[i].value = "취 소"
                comment_content[i].readOnly = false
            } else if (comment_update[i].value === "확 인") {
                comment_update[i].type = "submit"
            }
        })

        // 댓글 삭제 버튼 이벤트
        comment_delete[i].addEventListener('click', function() {
            // 삭제 함수 호출
            delete_button_change(comment_delete[i])
        })
    }
})