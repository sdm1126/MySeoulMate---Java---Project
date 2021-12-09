// html 코드가 모두 로드된 뒤 다음 코드 실행하는 이벤트
document.addEventListener('DOMContentLoaded', () =>{

    let select = document.querySelector('#select')
    let search_str = document.querySelector('#search_str')
    let error = document.querySelector('#error')
    let submit = document.querySelector('#submit')
    
    // 검색창 문자열 체크 이벤트 
    search_str.addEventListener('keyup', function() {
      let str = this.value
      // 작성일 기준으로 설정되었을 때 
      if (select.options[select.selectedIndex].value === 'written_date') {
        // ajax 실행
        var xhttp
        if (str == "") {
          error.innerHTML = ""
          return
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState === this.DONE && this.status === 200) {
            // 통신 성공시 호출 함수
            error.innerHTML = this.responseText
          }
        }
        // 실행할 php파일
        xhttp.open("GET", "search_str_check.php?q=" + str, true)
        xhttp.send()
    
      }
    })
    
    // 검색창에 문자열 제거 조건 이벤트
    submit.addEventListener('click', function(){
      // 작성일 기준으로 설정되었을 때
      if (select.options[select.selectedIndex].value === 'written_date'){
          if(error.innerHTML){
            // 검색창 비우기
            search_str.value = ''
          }
      }
    })
    })