import initRegionSelectionModal from './regionSelectionModal/regionSelectionModal';
import headerInit from './header-init/header-init';
import { initContactUsBtn, initShowPopup } from './feedback-popup/feedback-popup';

initRegionSelectionModal();
headerInit();
initContactUsBtn();

if (window.location.pathname === `${urlPrefix}/contact_us`) {
  initShowPopup();
}


