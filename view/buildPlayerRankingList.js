function buildPlayerRankingList(p_ranking){
	
	$("#playerRanking").empty();
	var li;
			
	for (var i = 0; i < p_ranking.length; i++) {
				
		var id = p_ranking[i]["id"];
		var name = p_ranking[i]["name"];
		var flower_points = p_ranking[i]["flower_points"];
				
		if (i==0){
			li = '<li globalScoreId="' + p_ranking[i]["id"] + '">' + p_ranking[i]["name"] + ' ('+p_ranking[i]["flower_points"]+')</li>';
		}else{
			li += '<li globalScoreId="' + p_ranking[i]["id"] + '">' + p_ranking[i]["name"] + ' ('+p_ranking[i]["flower_points"]+')</li>';
		}
	}
			
	$("#playerRanking").append(li);
	$("#playerRanking").listview("refresh");
	
	$("#toast-message").remove();
			
	//$.mobile.fixedToolbars.show(true);
	
}