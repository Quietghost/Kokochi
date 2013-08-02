function buildUsableEmotionsList(oldEmotions){
	
	var newhtml2 = "";
	
	newhtml2 +='<div data-role="collapsible" data-theme="b" data-content-theme="d">'+
							'<h3>'+textEmotionList+'</h3>'+
							'<p></p>';
	
	newhtml2 +='<ul data-role="listview" data-inset="true" data-theme="c">';
        
	for (var i = 0; i < oldEmotions.length; i++) {
	
		newhtml2 += buildMyEmotionsOverview(oldEmotions[i]["name"] + " (" + oldEmotions[i]["number"] + ")");
	}
	
	newhtml2 +='</ul>'+
	'</div>';
	
	$('#oldEmotions').empty();
	$('#oldEmotions').append(newhtml2);
	$('#oldEmotions').trigger('create');
}