jQuery(function($){
    $.timepicker.regional['de'] = {
	timeOnlyTitle: 'Zeit auswählen',
	timeText: 'Zeit',
	hourText: 'Stunde',
	minuteText: 'Minute',
	secondText: 'Sekunde',
	closeText: 'Fertig',
	currentText: 'Jetzt',
	ampm: false
    };
    $.timepicker.setDefaults($.timepicker.regional['de']);
});