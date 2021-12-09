document.addEventListener('DOMContentLoaded', () => {
  const secondEmail = document.querySelector('#email_second');
  const selectedEmail = document.querySelector('#selected_email');
  selectedEmail.addEventListener('change', (event) => {
      secondEmail.textContent = selectedEmail.value;
      secondEmail.value = selectedEmail.value;
  })
})

function numberMaxLength(e) {

  if (e.value.length > e.maxLength) {

      e.value = e.value.slice(0, e.maxLength);

  }

}