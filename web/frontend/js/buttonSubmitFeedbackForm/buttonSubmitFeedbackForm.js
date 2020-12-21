export default function initButtonSubmit(buttonForm) {
  if (buttonForm.length) {
    buttonForm.addClass('btn_with-loader')
    buttonForm.attr('disabled', true);
    buttonForm[0].innerHTML = '<img src="/frontend/img/gif/loader.gif" class="button-loader" />'
  }
}