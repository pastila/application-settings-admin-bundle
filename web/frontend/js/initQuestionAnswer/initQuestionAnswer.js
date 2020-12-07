export default function initQuestionAnswer() {
    $('.b-question__item-title').on('click', function (event) {
      event.preventDefault();
      var itemTab = $(this).closest('.b-question__item');
      itemTab.toggleClass('is-opened');
      itemTab.find('.b-question__item-text').slideToggle();
    });
}