function buildPlayerList(playerList){
	
	var newhtml3 = "";
	newhtml3 +='<div data-role="collapsible" data-theme="b" data-content-theme="d">'+
							'<h3>'+textPlayerListHead+'</h3>'+
							'<p></p>'+
							'<ul data-role="listview">';
							
	for (var i = 0; i < playerList.length; i++) {	
	
		newhtml3 += 	'<li data-role="list-divider"> '+textPlayerListLogin+' '+playerList[i]["last_login"]+'<span id="fpcounter-'+playerList[i]["player_id"]+'"><span id="fpcounter-content-'+playerList[i]["player_id"]+'" fp-counter="'+playerList[i]["flower_points"]+'" class="ui-li-count">'+playerList[i]["flower_points"]+'</span></span></li>'+
					'<li>'+
							'<h4>'+playerList[i]["name"]+'</h4>'+
							'<p><strong>'+textPlayerListEmotion+' </strong>'+playerList[i]["last_emotion_name"]+'</p>'+
							'<span id="hugcounter-'+playerList[i]["player_id"]+'"><p id="hugcounter-content-'+playerList[i]["player_id"]+'" hug-counter="'+playerList[i]["hugs"]+'" class="ui-li-aside"><strong>'+textPlayerListHugged+' </strong>'+playerList[i]["hugs"]+'</p></span>'+
							'<input type="button" value="'+buttonPlayerDetailedHug+'" id="hug" player-id="'+playerList[i]["player_id"]+'" data-inline="true">'+
					'</li>';
	}
			
	newhtml3 +='</ul></div>';
	
	$('#playerOverview').empty();
	$('#playerOverview').append(newhtml3);
	$('#playerOverview').trigger('create');
	
}