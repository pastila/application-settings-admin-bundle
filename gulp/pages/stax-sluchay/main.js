//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

$(document).ready(function() {

	$('#generate_form').magnificPopup({
        type: 'ajax',
        modal: true,

        callbacks: {
			beforeOpen: function() {
				if($(window).width() < 700) {
					this.st.focus = false;
				} else {
					this.st.focus = '#name';
				}
			}
		}
    });
});