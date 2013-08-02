function buildPlayerProfile(){
	
	//buildChargePowerPage();
	
	var newhtml ="";
	
	newhtml +='<div class="ui-grid-b">'+
		'<div id="last_emotion" class="playerprofile-last-emotion ui-block-a"><div class="player-profile-entry" id="emostatementtip"><img class ="playerprofile-sprite" alt="" src="view/img/emotion-sprite.png" /><br />'+lastemoname+'</div></div>'+
		'<div id="score" class="playerprofile-score ui-block-b"><div class="player-profile-entry" id="flowerpointstip"><img class ="playerprofile-sprite" alt="" src="view/img/flower-points-sprite.png" /><br />'+flowerPoints+'</div></div>'+
		'<div id="hugs" class="playerprofile-hugs ui-block-c"><div class="player-profile-entry" id="hugtip"><img class ="playerprofile-sprite" alt="" src="view/img/hugs-sprite.png" /><br />'+hugs+'</div></div>'+
	'</div>';
	newhtml +='<div class="ui-grid-b">'+
		'<div id="emocounter" class="playerprofile-emocounter ui-block-a"><div class="player-profile-entry" id="emostatementcounttip"><img class ="playerprofile-sprite" alt="" src="view/img/emocount-sprite.png" /><br />'+emocounter+'</div></div>'+
		'<div id="hugscounter" class="playerprofile-hugscounter ui-block-b"><div class="player-profile-entry" id="flowerpointscounttip"><img class ="playerprofile-sprite" alt="" src="view/img/hugscount-sprite.png" /><br />'+currentHugBase+'</div></div>'+
		'<div id="collaboration_number" class="playerprofile-collaboration ui-block-b"><div class="player-profile-entry" id="collaborationtip"><img class ="playerprofile-sprite" alt="" src="view/img/collaboration-sprite.png" /><br />'+collaborationNumber+'</div></div>'+
	'</div>';
	
	$('#playerProfile').empty();
	$('#playerProfile').append(newhtml);
	$('#playerProfile').trigger('create');
	
	/*var newhtml2 = "";
	
	newhtml2 +='<div data-role="collapsible" data-theme="b" data-content-theme="d">'+
							'<h3>Your usable emotions:</h3>'+
							'<p></p>';
	
	newhtml2 +='<ul data-role="listview" data-inset="true" data-theme="c">';
        
	for (var i = 0; i < oldEmotions.length; i++) {
	
		newhtml2 += buildMyEmotionsOverview(oldEmotions[i]["name"] + " (" + oldEmotions[i]["number"] + ")");
	}
	
	newhtml2 +='</ul>'+
	'</div>';
	
	$('#oldEmotions').empty();
	$('#oldEmotions').append(newhtml2);
	$('#oldEmotions').trigger('create');*/
	
	//alert(playerList.length);
	
	/*var newhtml3 = "";
	newhtml3 +='<div data-role="collapsible" data-theme="b" data-content-theme="d">'+
							'<h3>Player overview:</h3>'+
							'<p></p>'+
							'<ul data-role="listview">';
							
	for (var i = 0; i < playerList.length; i++) {	
	
		newhtml3 += 	'<li data-role="list-divider"> Last Login: '+playerList[i]["last_login"]+'<span class="ui-li-count">'+playerList[i]["score"]+'</span></li>'+
					'<li>'+
							'<h4>'+playerList[i]["name"]+'</h4>'+
							'<p><strong>Last emotion: </strong>'+playerList[i]["last_emotion_name"]+'</p>'+
							'<p class="ui-li-aside"><strong>Huged: </strong>'+playerList[i]["hugs"]+'</p>'+
							'<input type="button" value="Hug" id="hug" player-id="'+playerList[i]["player_id"]+'" data-inline="true">'+
					'</li>';
	}
			
	newhtml3 +='</ul></div>';
			
	$('#playerOverview').empty();
	$('#playerOverview').append(newhtml3);
	$('#playerOverview').trigger('create');*/
	
	//buildPlayerList(playerList);
	
}