$(document).ready(function() {

  function showComment() {
    const items = document.querySelectorAll('.white_block');

    items.forEach(function(item) {
      const btn = item.querySelector('.feedback__bottom_link');
      const showBlock = item.querySelector('.hidenComments');
      const count = item.querySelector('.comment-count');

      if (count && count.innerText > 0) {
        if (btn) {
          btn.addEventListener('click', function(e) {

            this.classList.toggle('active');

            if (btn.classList.contains('active')) {
              showBlock.classList.add('active');
            } else {
              showBlock.classList.remove('active');
            }
          });
        }
      } else {
        return
      }
    });
  }

  showComment();
  $('.toggle_comment_dropdown').on('click', function (e) {
    $(this).parent().toggleClass('openedBlock');
  });
});