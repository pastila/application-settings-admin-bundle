import initRegionSelectionModal from './regionSelectionModal/regionSelectionModal';
import headerInit from './header-init/header-init';
import { initContactUsBtn } from './feedback-popup/feedback-popup';
import initQuestionAnswer from './initQuestionAnswer/initQuestionAnswer';
import initButtonSubmit from './buttonSubmitFeedbackForm/buttonSubmitFeedbackForm';
import closeRemodal from './helper/closeRemodal';

initRegionSelectionModal();
headerInit();
initContactUsBtn();
initQuestionAnswer();
closeRemodal();

if (window.location.pathname === `${urlPrefix}/contact_us`) {
  const formContactUs = $('.contact-us-form form[name="contact_us"]');

  if (formContactUs.length) {
    formContactUs.attr('action', `${urlPrefix}/contact_us` );
    formContactUs.append('<button type="submit" class="mainBtn btn" id="ask">Отправить</button>')

    formContactUs.on('submit', () => {
      const buttonSubmitForm = $('.contact-us-form form[name="contact_us"] button[type="submit"]');
      initButtonSubmit(buttonSubmitForm);
    });
  }
}
