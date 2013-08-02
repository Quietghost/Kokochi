function buildLocalizationStatus(status){
	
	if (!status){
		$("#location_reception").empty();
		var htmlYourFlowerPoints = '<img src="view/img/location_bad.png" />';
		$("#location_reception").append(htmlYourFlowerPoints);
		$("#location_reception").trigger( "create" );
	}
	
}