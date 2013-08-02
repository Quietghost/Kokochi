function buildCollPageButtons(){
	
	$("#buttonCollPlayerPage").empty();
	 //'<input type="button" data-theme="c" id="collPlayersSubmit" value='+buttonCollReady+' data-inline="true"/>'+
    var newhtml =  '<input type="button" data-theme="c" id="collStart" value='+buttonCollStart+' data-inline="true"/>'+
			   '<input type="button" data-theme="a" id="collReset" value='+buttonCollReset+' data-inline="true"/>';
	$("#buttonCollPlayerPage").append(newhtml);
	$("#buttonCollPlayerPage").trigger("create");

}