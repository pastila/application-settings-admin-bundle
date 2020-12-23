/*
* Данная функция заменяет кастомное закрытие модального окна (remodal),
* которое приводило к закрытию окна при mousedown на самом модальном окне
* и mouseup на overlay.
*
* Используется с модальными окнами созданными с помощью remodal.
* Для использования нужно задать дефолтное свойство закрытия модального окна closeOnOutsideClick: false.
*/

function onEventMouseDown(event) {
  console.log('false');
  if (event.target.classList.contains('remodal-wrapper')) {
    $(event.target).find('.remodal').remodal().close();
  }
}

export default function() {
  $(document).on('opening', '.remodal', function () {
    $('.remodal-wrapper').on('mousedown', (event) => {
      onEventMouseDown(event);
    });
  });

  $(document).on('closing', '.remodal', function () {
    $('.remodal-wrapper').off('mousedown');
  });
}