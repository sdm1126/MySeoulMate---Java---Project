document.addEventListener('DOMContentLoaded', () => {
  const findId = document.querySelector('#findId');
  const findPassword = document.querySelector('#findPassword');
  findId.addEventListener('click', (e) => {
      location.replace('./find_id.php');
  })
  findPassword.addEventListener('click', (e) => {
      location.replace('./find_password.php');
  })
})
$(document).ready(function() {
  var userInputId = getCookie("userInputId"); //저장된 쿠기값 가져오기
  $("input[name='id']").val(userInputId);

  if ($("input[name='id']").val() != "") { // 그 전에 ID를 저장해서 처음 페이지 로딩
      // 아이디 저장하기 체크되어있을 시,
      $("#idSaveCheck").attr("checked", true); // ID 저장하기를 체크 상태로 두기.
  }

  $("#idSaveCheck").change(function() { // 체크박스에 변화가 발생시
      if ($("#idSaveCheck").is(":checked")) { // ID 저장하기 체크했을 때,
          var userInputId = $("input[name='id']").val();
          setCookie("userInputId", userInputId, 7); // 7일 동안 쿠키 보관
      } else { // ID 저장하기 체크 해제 시,
          deleteCookie("userInputId");
      }
  });

  // ID 저장하기를 체크한 상태에서 ID를 입력하는 경우, 이럴 때도 쿠키 저장.
  $("input[name='id']").keyup(function() { // ID 입력 칸에 ID를 입력할 때,
      if ($("#idSaveCheck").is(":checked")) { // ID 저장하기를 체크한 상태라면,
          var userInputId = $("input[name='id']").val();
          setCookie("userInputId", userInputId, 7); // 7일 동안 쿠키 보관
      }
  });
});

function setCookie(cookieName, value, exdays) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var cookieValue = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toGMTString());
  document.cookie = cookieName + "=" + cookieValue;
}

function deleteCookie(cookieName) {
  var expireDate = new Date();
  expireDate.setDate(expireDate.getDate() - 1);
  document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString();
}

function getCookie(cookieName) {
  cookieName = cookieName + '=';
  var cookieData = document.cookie;
  var start = cookieData.indexOf(cookieName);
  var cookieValue = '';
  if (start != -1) {
      start += cookieName.length;
      var end = cookieData.indexOf(';', start);
      if (end == -1) end = cookieData.length;
      cookieValue = cookieData.substring(start, end);
  }
  return unescape(cookieValue);
}