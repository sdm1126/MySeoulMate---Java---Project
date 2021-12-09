// html 코드가 모두 로드된 뒤 다음 코드 실행하는 이벤트
document.addEventListener('DOMContentLoaded', () => {

    let check1 = document.querySelector('#check1')
    let submit = document.querySelector('#submit')
    let message = document.querySelector('#message')
    
    //checkbox 값을 확인해 태그를 추가하는 이벤트
    check1.addEventListener('click', function(){
        if(check1.checked){
            submit.disabled = false
            message.innerHTML = ""
        }else{
            submit.disabled = true
            message.innerHTML = '<span style="color: red; font-size: 14px;">필수 동의 사항입니다</span>'
        }
    })
})