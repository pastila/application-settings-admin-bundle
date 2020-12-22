export default function initRegionSelectionModal() {
    const locationWindow = $('[data-remodal-id=loacation]').remodal();

    locationWindow.settings = {
        hashTracking: false,
        closeOnOutsideClick: false,
    };

    $('.js-loacation').on('click', function (event) {
        event.preventDefault();
        if (locationWindow) {
            locationWindow.open();
        }
    });
    const regionModalError = $('[data-remodal-id=error-modal]').remodal();
    $('.l-list__item a').on('click', function (event) {
        event.preventDefault();
        if (locationWindow) {
            locationWindow.close();
        }
        $.get(`${urlPrefix}/select-location`, {region_id: event.target.dataset.region})
            .done((data) => {
                if (data === 1) {
                    $('.js-location').html($(this).html());
                    window.location.reload();
                } else {
                    if (regionModalError) {
                        regionModalError.open();
                    }
                }
            })
            .fail(() => {
                if (regionModalError) {
                    regionModalError.open();
                }
            })
    });
}