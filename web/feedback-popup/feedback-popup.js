class PopupContactUs {
  constructor(popupElement) {
    this.popupElement = popupElement
  }

  open() {
    if (!this.popupElement.querySelector(`.feedback_form`)) {
      const renderPopupMarkup = () => {
        $.ajax({
          dataType: 'html',
          url: `${urlPrefix}/contact_us`,
          type: 'GET',
          beforeSend: function () {
          },
          success: (result) => {
            this.popupElement.insertAdjacentHTML(`beforeend`, result);
            this.submitForm(changePopupEventsState);
          },
          error: () => {
            if (!this.popupElement.querySelector(`.feedback_form`)){
              this.popupElement.insertAdjacentHTML(`beforeend`, `<form class="feedback_form"></form>`);
              this.popupElement.querySelector(`form`).insertAdjacentHTML(`beforeend`, `<p class="popup-write-us__error-message">Произошла ошибка попробуйте повторить позже</p>`);
            }
          },
        }).done(function (msg) {
        });
      };

      const changePopupEventsState = (type) => {
        const method = type ? `addEventListener` : `removeEventListener`;
        this.popupElement[method](`click`, onCloseBtnClick);
        this.popupElement[method](`click`, onPopupElementClick);
        document[method](`keydown`, onEscPress);
      };

      this.popupElement.style.display = "flex";
      renderPopupMarkup(this.popupElement);
      const onCloseBtnClick = (evt) => {
        const target = evt.target;
        const closeBtn = this.popupElement.querySelector(`.close-modal`);
        const closeBtnImg = this.popupElement.querySelector(`.close-modal img`);
        if (target === closeBtn || target === closeBtnImg) {
          this.close();
          changePopupEventsState(false);
        }
      };

      const onPopupElementClick = (evt) => {
        if (evt.target === this.popupElement) {
          evt.preventDefault();
          this.close();
          changePopupEventsState(false);
        }
      }

      const onEscPress = (evt) => {
        if (evt.key === `Escape`) {
          evt.preventDefault();
          this.close();
          changePopupEventsState(false);
        }
      }

      changePopupEventsState(true);
    }
  };

  close() {
    if(this.popupElement.querySelector(`.feedback_form`)) {
      this.popupElement.style.display = "none";
      this.popupElement.querySelector(`.feedback_form`).remove();
    }
  };

  submitForm(removeEventListeners) {
    if(this.popupElement.querySelector(`form`)){
      this.popupElement.querySelector(`form`).addEventListener(`submit`, (evt) => {
        evt.preventDefault();
        $.ajax({
          url: `${urlPrefix}/contact_us`,
          type: 'POST',
          beforeSend: function () {
          },
          data: $(this.popupElement.querySelector(`form`)).serialize(),
          success: () => {
            this.close();
            removeEventListeners(false);
          },
          error: () => {
            if (!this.popupElement.querySelector(`.popup-write-us__error-message`)){
              this.popupElement.querySelector(`.popup__wrap`).remove();
              this.popupElement.querySelector(`form`).insertAdjacentHTML(`beforeend`, `<p class="popup-write-us__error-message">Произошла ошибка попробуйте повторить позже</p>`);
            }
          },
        }).done(function (msg) {
        });
      });
    }
  }
}

export function initContactUsBtn() {
  const contactUsBtn = document.querySelector(`.app-footer__contact-us-btn`);
  const popupElement = document.querySelector(`.popup-write-us`);

  if (contactUsBtn && popupElement) {
    function onContactUsBtnClick () {
      const popupContuctUs = new PopupContactUs(popupElement);
      popupContuctUs.open();
      popupContuctUs.submitForm();
    }

    contactUsBtn.addEventListener(`click`, onContactUsBtnClick)
  }
}

export function initShowPopup() {
  const popupElement = document.querySelector(`.popup-write-us`);

  if (popupElement) {
    setTimeout(() => {
      const popupContuctUs = new PopupContactUs(popupElement);
      popupContuctUs.open();
      popupContuctUs.submitForm();
    }, 2000);
  }
}


