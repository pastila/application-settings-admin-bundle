setTimeout(showFeedbackForm, 300)

function showFeedbackForm() {

  const popupElement = document.createElement(`div`);
  popupElement.classList.add(`popup-write-us`);

  $.ajax({
    dataType: 'html',
    url: '/app_dev.php/modal-us',
    type: 'POST',
    beforeSend: function () {
    },
    success: function (result) {
      popupElement.insertAdjacentHTML(`beforeend`, result);
      document.body.insertAdjacentElement(`beforeend`, popupElement);
      const closeBtn = popupElement.querySelector(`.close-modal`);

      closeBtn.addEventListener(`click`, onCloseBtnClick);
      function onCloseBtnClick (evt) {
        evt.preventDefault();
        closeBtn.removeEventListener(`click`, onCloseBtnClick);
        popupElement.removeEventListener(`click`, onPopupElementClick);
        document.removeEventListener(`click`, onEscPress);
        popupElement.remove();
      }

      popupElement.addEventListener(`click`, onPopupElementClick);
      function onPopupElementClick (evt) {
        if(evt.target===popupElement) {
          evt.preventDefault();
          closeBtn.removeEventListener(`click`, onCloseBtnClick);
          popupElement.removeEventListener(`click`, onPopupElementClick);
          document.removeEventListener(`click`, onEscPress);
          popupElement.remove();
        }
      }

      document.addEventListener(`keydown`, onEscPress);
      function onEscPress(evt) {
        if (evt.key === `Escape`) {
          evt.preventDefault();
          closeBtn.removeEventListener(`click`, onCloseBtnClick);
          popupElement.removeEventListener(`click`, onPopupElementClick);
          document.removeEventListener(`click`, onEscPress);
          popupElement.remove();
        }
      }
    },
  }).done(function (msg) {
  });
};
