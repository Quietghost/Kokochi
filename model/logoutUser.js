function logoutUser(){
	
	//Clear all my entries from the player_id_collCurrent tables of the other players
	for (var i=0;i<collPlayerIdStringSortArrayWithoutMe.length;i++){

		var paramsClear = "tableName=player_" + collPlayerIdStringSortArrayWithoutMe[i] + "_collCurrent";
		ajaxCall("clearTable",paramsClear);
	}

	collPlayerName = "";
	collPlayerIdString = "";
	collPlayerIdStringSortArray = new Array();
	collPlayerIdStringSortArrayWithoutMe = new Array();
	
	deleteOverlays();
	
	$('input:checkbox:checked').each(function() {
		$(this).attr('checked', false);
		$(this).checkboxradio("refresh");
	});
	
	reloadMap = true;
	emoTable = "emotions";
	
	//sessionStorage.clear()
	$.mobile.changePage('#start', { transition: "slide"} );
}