(function($) {
	$(function() {
		
		// Check to make sure the input box exists
		if( 0 < $('#datepicker').length ) {
			$('#datepicker').datetimepicker();
		} // end if
		
	});
}(jQuery));