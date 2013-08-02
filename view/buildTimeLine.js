function buildTimeLine(pastEvents){
	var newhtml ='<ul data-role="listview" data-inset="true">';
	
	for (var i = 0; i < pastEvents.length; i++) {
		switch (pastEvents[i]["id"]){
			
			case "emo":
			newhtml += 	
					'<li data-theme="d">'+
							'<h4> '+textTimelineEmotionHead+' </h4>'+
							'<p>'+pastEvents[i]["player"]+' '+textTimelineEmotionAction+' '+pastEvents[i]["emotion"]+'</p>'+
							'<p>'+pastEvents[i]["timestamp"]+'</p>'+
					'</li>';
			break;
			case "coll":
			newhtml += 	
					'<li data-theme="b">'+
							'<h4> '+textTimelineRitualHead+' </h4>'+
							'<p>'+pastEvents[i]["collplayers"]+' '+textTimelineHugAction+'</p>'+
							'<p>'+pastEvents[i]["timestamp"]+'</p>'+
					'</li>';
			break;
			case "hug":
			newhtml += 	
					'<li data-theme="e">'+
							'<h4> '+textTimelineHugHead+' </h4>'+
							'<p>'+pastEvents[i]["sender"]+' '+textTimelineRitualAction+' '+pastEvents[i]["reciever"]+'</p>'+
							'<p>'+pastEvents[i]["timestamp"]+'</p>'+
					'</li>';
			break;
		}
	}
			
	newhtml +='</ul>';
	
	$('#allTimeline').empty();
	$('#allTimeline').append(newhtml);
	$('#allTimeline').trigger('create');
	
	$("#toast-message").remove();
	
	//$.mobile.fixedToolbars.show(true);
}