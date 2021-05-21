(function($){
    $(function(){
        $(".formarea__title__question-mark__image").tooltip({
            tooltipClass: "jq-tooltip",
            position: { my: "left+18 bottom-18" }
        })
        $(".form-item__radio__labelarea__question-mark__image").tooltip({
            tooltipClass: "jq-tooltip jq-tooltip_big",
            position: { my: "left+18 bottom-18" }
        })
        $(".form-item_phone-field__question-mark").tooltip({
            tooltipClass: "jq-tooltip jq-tooltip_big",
            position: { my: "left+18 bottom-18" }
        })
        /*$('#oms_charge_complaint1st_step_region').autocomplete({
            source: function(request, response){
                var term = request.term;
                $.ajax({
                    url: '/api/v1/regions',
                    data: {
                        query: term
                    },
                    success: function (items, result, xhr){
                        var data = [];
                        $.each(items, function (index, item){
                            data.push({
                                label: item.name,
                                value: item.id
                            });
                        });
                        response(data);
                    }
                });
            },
            // classes: {
            //     'ui-autocomplete': 'jq-selectbox__dropdown'
            // }
        });*/
    })
})(jQuery)
