class PopupContactUs {
  constructor(popupElement) {
    this.popupElement = popupElement
  }

  open() {
    if (!this.popupElement.querySelector(`.feedback_form`)) {
      const renderPopupMarkup = (cb) => {
        $.ajax({
          dataType: 'html',
          url: `${urlPrefix}/contact_us`,
          type: 'GET',
          beforeSend: function () {
          },
          success: (result) => {
            cb()
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

      const removeChildrens = () => {
        while(this.popupElement.firstChild) {
          this.popupElement.firstChild.remove();
        }
      }

      const changePopupEventsState = (type) => {
        const method = type ? `addEventListener` : `removeEventListener`;
        this.popupElement[method](`click`, onCloseBtnClick);
        this.popupElement[method](`mousedown`, onPopupElementClick);
        document[method](`keydown`, onEscPress);
      };

      this.popupElement.style.display = "flex";
      this.popupElement.insertAdjacentHTML(`beforeend`, `<p class="load-message">Форма загружается</p>`)
      renderPopupMarkup(removeChildrens);
      const onCloseBtnClick = (evt) => {
        const target = evt.target;
        const closeBtn = this.popupElement.querySelector(`.remodal-close`);
        if (target === closeBtn) {
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

      const isSomeFieldInFocus = () => {
        let focusField = false;
        const formFieldElements = this.popupElement.querySelector(`form`).querySelectorAll(`[name]`);
        formFieldElements.forEach((element) => {
          if(element === document.activeElement) {
            focusField = true
          }
        })
        return focusField;
      }

      const onEscPress = (evt) => {
        if (evt.key === `Escape` && this.popupElement.querySelector(`form`)) {
          if(!isSomeFieldInFocus()) {
            evt.preventDefault();
            this.close();
            changePopupEventsState(false);
          }
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
      const formElement = this.popupElement.querySelector(`form`);
      const formFieldElements = formElement.querySelectorAll(`[name]`);
      formElement.addEventListener(`submit`, (evt) => {
        let emptyField = false;
        evt.preventDefault();
        formFieldElements.forEach((element) => {
          if (!element.value) {
            element.style.outline = `1px solid red`;
            element.parentElement.classList.add(`error`)
            emptyField = true;
          } else {
            element.style.outline = `none`;
            element.parentElement.classList.remove(`error`)
          }
        })
        if (!emptyField) {
          $.ajax({
            url: `${urlPrefix}/contact_us`,
            type: 'POST',
            beforeSend: function () {
            },
            data: $(this.popupElement.querySelector(`form`)).serialize(),
            success: () => {
              while (this.popupElement.querySelector(`form`).firstChild) {
                this.popupElement.querySelector(`form`).firstChild.remove();
              }
              this.popupElement.querySelector(`form`).insertAdjacentHTML(`beforeend`, `<p class="success-message">Спасибо за обращенение!</p>`);
              let delay;
              delay  = setInterval(() => {
                this.close();
                removeEventListeners(false);
                delay = clearTimeout(delay)
              }, 2000)
            },
            error: () => {
              if (!this.popupElement.querySelector(`.popup-write-us__error-message`)){
                this.popupElement.querySelector(`.popup__wrap`).remove();
                this.popupElement.querySelector(`form`).insertAdjacentHTML(`beforeend`, `<p class="popup-write-us__error-message">Произошла ошибка попробуйте повторить позже</p>`);
              }
            },
          }).done(function (msg) {
          });
        }
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
