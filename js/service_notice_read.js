document.addEventListener('DOMContentLoaded', () => {
  const title = document.querySelector('#title'); // 검색 버튼(submit)
  const content = document.querySelector('#content'); // 검색 버튼(submit)
  const submit = document.querySelector('.submit'); // 검색 버튼(submit)

  // 제목 미입력 상태로 내용 클릭 시
  content.addEventListener('click', () => {
    if(title.value.trim().length < 1) {
      alert('제목을 입력해주세요.');
      title.focus();
    }
  })

  // 제목 미입력 상태로 글쓰기 버튼 클릭 시
  submit.addEventListener('click', () => {
    if(title.value.trim().length < 1) {
      alert('제목을 입력해주세요.');
      title.focus();
    }
  })
})