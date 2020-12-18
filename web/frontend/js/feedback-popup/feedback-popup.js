import initButtonSubmit from '../buttonSubmitFeedbackForm/buttonSubmitFeedbackForm';

class PopupContactUs {
  constructor(popupElement) {
    this.popupElement = popupElement;
    this.popupAction = popupElement.remodal();
    this.popupContent = popupElement.find('.remodal-content');
  }

  open() {
    this.popupAction.open();

    $(document).on('closing', '.remodal', () => {
      changePopupEventsState(false);
    });

    if (this.popupContent[0]) {
      this.popupContent[0].innerHTML = '<p class="load-message">Форма загружается<p/>';
    }

    $.ajax({
        dataType: 'html',
        url: `${urlPrefix}/contact_us`,
        type: 'GET',
    }).done((result) => {
      if (result && this.popupContent[0]) {
        this.popupContent[0].innerHTML = `<div class="remodal-title">Напишите нам</div>${result}`;
        this.popupContent.find('form').append('<button type="submit" class="mainBtn btn" id="ask">Отправить</button>')
      }

      this.submitForm();
    }).fail(() => {
      if (this.popupContent[0]) {
        this.popupContent[0].innerHTML = '<p class="remodal__error-message">Произошла ошибка попробуйте повторить позже</p>';
      }
    });

    const changePopupEventsState = (type) => {
      const method = type ? 'on' : 'off';
      $(document)[method](`keydown`, onEscPress);
    };

    const isSomeFieldInFocus = () => {
      let focusField = false;
      const formFieldElements = this.popupElement.find('form [name]');

      formFieldElements.each((index, element) => {
        if(element === document.activeElement) {
          focusField = true;
        }
      })
      return focusField;
    }

    const onEscPress = (evt) => {
      if (evt.key === `Escape` && !isSomeFieldInFocus()) {
          this.popupAction.close();
      }
    }

    changePopupEventsState(true);
  };

  submitForm() {
      const formElement = this.popupContent.find('form')[0];

      if (formElement) {
        formElement.addEventListener(`submit`, (evt) => {
          evt.preventDefault();

          const buttonSubmitForm = this.popupContent.find('form[name="contact_us"] button[type="submit"]');

          initButtonSubmit(buttonSubmitForm);

          $.ajax({
            url: `${urlPrefix}/contact_us`,
            type: 'POST',
            data: $(this.popupContent.find('form')[0]).serialize(),
          }).done(() => {
            this.popupContent[0].innerHTML = '<p class="success-message">Ваше сообщение было успешно отправлено.</p>'
          }).fail((err) => {
            if (err && err.status === 400 && err.responseText) {
              this.popupContent.find('form')[0].innerHTML = err.responseText;
              this.popupContent.find('form').append('<button type="submit" class="mainBtn btn" id="ask">Отправить</button>')
            }
          })
        });
      }
  }
}

export function initContactUsBtn() {
  const contactUsBtn = document.querySelector(`.app-footer__contact-us-btn`);
  const popupElement = $('[data-remodal-id=contact-us-modal]');

  if (contactUsBtn && popupElement.length) {
    function openContactUsModal () {
      const popupContuctUs = new PopupContactUs(popupElement);
      popupContuctUs.open();
    }

    contactUsBtn.addEventListener(`click`, openContactUsModal);
  }
}
