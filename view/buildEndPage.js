function buildEndPage(endOfGameStatus){
	
	var newhtml ="";
	
	if(endOfGameStatus[0]["my_ranking"] != "1"){
		newhtml += '<span class="end-of-game-status-text">The top player: '+endOfGameStatus[0]["top_player"]+'</span></p>'+
					'<span class="end-of-game-status-text">The top players flower points: '+endOfGameStatus[0]["top_player_flower_points"]+'</span></p>';			
	}
	
	newhtml +='<span class="end-of-game-status-text"> My Rank: '+endOfGameStatus[0]["my_ranking"]+' </span></p>'+
				'<span class="end-of-game-status-text">My flower points: '+endOfGameStatus[0]["my_flower_points"]+'</span></p>'+
				'<span class="end-of-game-status-text">Number of emotions I stated: '+endOfGameStatus[0]["number_of_emotions"]+'</span></p>'+
				'<span class="end-of-game-status-text">Number of rituals I performed: '+endOfGameStatus[0]["number_of_collaborations"]+'</span></p>'+
				'<span class="end-of-game-status-text">Number of hugs I recieved: '+endOfGameStatus[0]["number_of_hugs_recieved"]+'</span></p>'+
				'<span class="end-of-game-status-text">Number of hugs I send: '+endOfGameStatus[0]["number_of_hugs_send"]+'</span></p>';
			
	$('#statusInfo').empty();
	$('#statusInfo').append(newhtml);
	$('#statusInfo').trigger('create');
	
}