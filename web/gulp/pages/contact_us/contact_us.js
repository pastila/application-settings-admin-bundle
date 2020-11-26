
let popupHTML = null

$.ajax({
  dataType: 'html',
  url: '/contact_us',
  type: 'GET',
  beforeSend: function () {
  },
  success: function (result) {

    popupHTML= result;
    setTimeout(showFeedbackForm, 2000);

  },
}).done(function (msg) {
});

function showFeedbackForm() {

  function changePopupEventsState(isAddType) {
    const method = isAddType === true ? `addEventListener` : `removeEventListener`;

    closeBtn[method](`click`, onCloseBtnClick);
    popupElement[method](`click`, onPopupElementClick);
    document[method](`click`, onEscPress);
  }

  const popupElement = document.createElement(`div`);
  popupElement.classList.add(`popup-write-us`);
  popupElement.insertAdjacentHTML(`beforeend`, popupHTML);
  document.body.insertAdjacentElement(`beforeend`, popupElement);
  const closeBtn = popupElement.querySelector(`.close-modal`);


  function onCloseBtnClick (evt) {
    evt.preventDefault();
    changePopupEventsState(false)
    popupElement.remove();
  }

  function onPopupElementClick (evt) {
    if(evt.target===popupElement) {
      evt.preventDefault();
      changePopupEventsState(false);
      popupElement.remove();
    }
  }

  function onEscPress(evt) {
    if (evt.key === `Escape`) {
      evt.preventDefault();
      changePopupEventsState(false);
      popupElement.remove();
    }
  }

  changePopupEventsState(true);

};
