const popupElement = document.querySelector(`.popup-write-us`);

setTimeout(() => {
  const popupContuctUs = new PopupContactUs(popupElement);
  popupContuctUs.open();
  popupContuctUs.submitForm();
}, 2000);
