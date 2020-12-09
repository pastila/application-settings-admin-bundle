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
            if (result) {
              this.popupElement.innerHTML = result;
            }

            this.submitForm(changePopupEventsState);
          },
          error: () => {
            this.popupElement.innerHTML = '<div class="remodal">\n' +
                '    <div class="remodal-close">\n' +
                '        <div data-action="close-modal" class="svg-icon svg-icon--close" aria-hidden="true">\n' +
                '            <svg data-action="close-modal" class="svg-icon__link">\n' +
                '                <use data-action="close-modal" xlink:href="#close"></use>\n' +
                '            </svg>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '<p class="popup-write-us__error-message">Произошла ошибка попробуйте повторить позже</p>\n' +
                '</div>';
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
      this.popupElement.innerHTML = `<p class="load-message">Форма загружается</>`;
      renderPopupMarkup(removeChildrens);
      const onCloseBtnClick = (evt) => {
        const target = evt.target.dataset.action;

        if (target === 'close-modal') {
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
      if (!this.popupElement.classList.contains(`onpage`)) {
        changePopupEventsState(true);
      }
    }
  };

  close() {
    if(this.popupElement && this.popupElement.querySelector('.remodal')) {
      this.popupElement.style.display = "none";
      this.popupElement.querySelector('.remodal').style.display = 'none';
      if (this.popupElement.querySelector(`.feedback_form`)) {
        this.popupElement.querySelector(`.feedback_form`).remove();
      }
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
              this.popupElement.innerHTML = '<div class="remodal">\n' +
                  '    <div class="remodal-close">\n' +
                  '        <div data-action="close-modal" class="svg-icon svg-icon--close" aria-hidden="true">\n' +
                  '            <svg data-action="close-modal" class="svg-icon__link">\n' +
                  '                <use data-action="close-modal" xlink:href="#close"></use>\n' +
                  '            </svg>\n' +
                  '        </div>\n' +
                  '    </div>\n' +
                  '<p class="popup-write-us__error-message">Спасибо за обращение!</p>\n' +
                  '</div>';
              let delay;
              delay  = setInterval(() => {
                this.close();
                if (removeEventListeners) {
                  removeEventListeners(false);
                }

                delay = clearTimeout(delay)
              }, 2000)
            },
            error: (err) => {
              if (err && err.status === 400 && err.responseText) {
                this.popupElement.innerHTML = err.responseText;
                this.submitForm();
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
