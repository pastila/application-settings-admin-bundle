class PopupContactUs {

  constructor(popupElement) {
    this.popupElement = popupElement
  }

  open() {
    if (!this.popupElement.querySelector(`.feedback_form`)) {
      const renderPopupMarkup = () => {
        $.ajax({
          dataType: 'html',
          url: 'contact_us',
          type: 'GET',
          beforeSend: function () {
          },
          success: (result) => {
            this.popupElement.insertAdjacentHTML(`beforeend`, result);
            this.submitForm();
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
      this.popupElement.style.display = "flex";
      renderPopupMarkup(this.popupElement);
      const onCloseBtnClick = (evt) => {
        const target = evt.target;
        const closeBtn = this.popupElement.querySelector(`.close-modal`);
        const closeBtnImg = this.popupElement.querySelector(`.close-modal img`);
        if (target === closeBtn || target === closeBtnImg) {
          this.close();
          this.popupElement.removeEventListener(`click`, onCloseBtnClick);
        }
      };

      const onPopupElementClick = (evt) => {
        if (evt.target === this.popupElement) {
          evt.preventDefault();
          this.close();
          this.popupElement.removeEventListener(`click`, onPopupElementClick);
        }
      }

      const onEscPress = (evt) => {
        if (evt.key === `Escape`) {
          evt.preventDefault();
          this.close();
        }
      }

      document.addEventListener(`keydown`, onEscPress);
      this.popupElement.addEventListener(`click`, onPopupElementClick);
      this.popupElement.addEventListener(`click`, onCloseBtnClick);
    }
  };

  close() {
    if(this.popupElement.querySelector(`.feedback_form`)) {
      this.popupElement.style.display = "none";
      this.popupElement.querySelector(`.feedback_form`).remove();
    }
  };

  submitForm() {
    if(this.popupElement.querySelector(`form`)){
      this.popupElement.querySelector(`form`).addEventListener(`submit`, (evt) => {
        evt.preventDefault();
        $.ajax({
          url: 'contact_us',
          type: 'POST',
          beforeSend: function () {
          },
          data: $(this.popupElement.querySelector(`form`)).serialize(),
          success: () => {
            this.close();
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
};
