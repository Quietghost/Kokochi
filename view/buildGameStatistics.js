function buildGamestats(gamestats){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="false">'+
				'<h3>Game Stats</h3>';
	newhtml += '<ul data-role="listview" data-inset="true">';
	
	for (var i = 0; i < gamestats.length; i++) {
		//newhtml += 	
			//'<li data-theme="d">'+
				//'<li> Game Stats </li>'+
				newhtml +='<li> Active players: '+gamestats[i]["active_players"]+' </li>'+
				'<li> Number of registered players: '+gamestats[i]["all_players"]+'</li>'+
				'<li> Number of emotions: '+gamestats[i]["number_of_emotions"]+'</li>'+
				'<li> Number of positive emotions: '+gamestats[i]["number_of_positive_emotions"]+'</li>'+
				'<li> Number of negative emotions: '+gamestats[i]["number_of_negative_emotions"]+'</li>'+
				'<li> Number of collaborations: '+gamestats[i]["number_of_collaborations"]+'</li>'+
				'<li> Number of hugs send: '+gamestats[i]["number_of_hugs_send"]+'</li>'+
				'<li> Number of days played: '+gamestats[i]["number_of_days_played"]+'</li>'+
				'<li> Number of players that collaborated: '+gamestats[i]["number_of_coll_players"]+'</li>';
			//'</li>';
	}
	
	newhtml +='</ul></div>';
	
	$('#gamestats').empty();
	$('#gamestats').append(newhtml);
	$('#gamestats').trigger('create');
}

function buildPlayerstats(players){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="true">'+
				'<h3>Player Stats</h3>';
	//newhtml += '<ul data-role="listview">';
	
	for (var i = 0; i < players.length; i++) {
		//newhtml += 	
			//'<li data-theme="d">'+
			newhtml += '<ul data-role="listview" data-inset="true">'+
				'<li> '+players[i]["name"]+'</li>'+
				'<li> id: '+players[i]["id"]+' </li>'+
				'<li> my_flower_points: '+players[i]["my_flower_points"]+'</li>'+
				'<li> number_of_emotions: '+players[i]["number_of_emotions"]+'</li>'+
				'<li> number_of_collaborations: '+players[i]["number_of_collaborations"]+'</li>'+
				'<li> number_of_hugs_send: '+players[i]["number_of_hugs_send"]+'</li>'+
				'<li> number_of_hugs_recieved: '+players[i]["number_of_hugs_recieved"]+'</li>'+
			'</ul>';
	}
	
	newhtml +='</div>';
	
	$('#playerstats').empty();
	$('#playerstats').append(newhtml);
	$('#playerstats').trigger('create');
}

function buildTimeline(timelineArray){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="true">'+
				'<h3>Emotions Timeline</h3>';
	//newhtml += '<ul data-role="listview">';
	
	for (var i = 0; i < timelineArray.length; i++) {
		newhtml += 	
			//'<li data-theme="d">'+
			'<ul data-role="listview" data-inset="true">'+
				'<li> '+timelineArray[i]["day"]+' </li>'+
				'<li> total: '+timelineArray[i]["total"]+'</li>'+
			'</ul>';
	}
	
	newhtml +='</div>';
	
	$('#timeline').empty();
	$('#timeline').append(newhtml);
	$('#timeline').trigger('create');
}

function buildEmotionstats(emotions){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="true">'+
				'<h3>Emotion Stats</h3>';
	//newhtml += '<ul data-role="listview">';
	
	for (var i = 0; i < emotions.length; i++) {
		newhtml += 	
			//'<li data-theme="d">'+
			'<ul data-role="listview" data-inset="true">'+
				'<li> '+emotions[i]["name"]+' </li>'+
				'<li> total: '+emotions[i]["count"]+'</li>'+
				'<li> type: '+emotions[i]["type"]+'</li>'+
			'</ul>';
	}
	
	newhtml +='</div>';
	
	$('#emotionstats').empty();
	$('#emotionstats').append(newhtml);
	$('#emotionstats').trigger('create');
}

function buildCollTimeline(collTimelineArray){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="true">'+
				'<h3>Coll Timeline</h3>';
	//newhtml += '<ul data-role="listview">';
	
	for (var i = 0; i < collTimelineArray.length; i++) {
		newhtml += 	
			//'<li data-theme="d">'+
			'<ul data-role="listview" data-inset="true">'+
				'<li> '+collTimelineArray[i]["day"]+' </li>'+
				'<li> total: '+collTimelineArray[i]["total"]+'</li>'+
			'</ul>';
	}
	
	newhtml +='</div>';
	
	$('#coll_timeline').empty();
	$('#coll_timeline').append(newhtml);
	$('#coll_timeline').trigger('create');
}

function buildHugTimeline(hugTimelineArray){
	
	var newhtml ='<div data-role="collapsible" data-collapsed="true">'+
				'<h3>Hug Timeline</h3>';
	//newhtml += '<ul data-role="listview">';
	
	for (var i = 0; i < hugTimelineArray.length; i++) {
		newhtml += 	
			//'<li data-theme="d">'+
			'<ul data-role="listview" data-inset="true">'+
				'<li> '+hugTimelineArray[i]["day"]+' </li>'+
				'<li> total: '+hugTimelineArray[i]["total"]+'</li>'+
			'</ul>';
	}
	
	newhtml +='</div>';
	
	$('#hug_timeline').empty();
	$('#hug_timeline').append(newhtml);
	$('#hug_timeline').trigger('create');
}