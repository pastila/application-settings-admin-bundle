export default function(selector) {
  $(selector).on('error', (e) => {
    $(e.target).remove();
  })
}