import initRegionSelectionModal from '../regionSelectionModal/regionSelectionModal';
import { initContactUsBtn, initShowPopup } from '../feedback-popup/feedback-popup';

initRegionSelectionModal();

initContactUsBtn();

if (window.location.pathname === `${urlPrefix}/contact_us`) {
  initShowPopup();
}


