function buildCollPlayerList(blockedPlayerListNames, collPlayerArray){
	
	$("#yourCurrentFlowerPints").empty();
	var htmlYourFlowerPoints = "<center>"+textCollPage+" Flower Points: " + flowerPoints+"<center>";
	$("#yourCurrentFlowerPints").append(htmlYourFlowerPoints);
	$("#yourCurrentFlowerPints").trigger( "create" );
	
	$('#collStart').button('disable');
		
	if (blockedPlayerListNames.length != 0){
		
		for (var i = 0; i < blockedPlayerListNames.length; i++) {
			if (i==0){
				newhtml = buildBlockedPlayerList(blockedPlayerListNames[i]);
			}else{
				newhtml += buildBlockedPlayerList(blockedPlayerListNames[i]);
			}
		}
		
		$("#textCollBlocked").empty();
		$("#textCollBlocked").append("</p><h2>"+textCollBlocked+"</h2>");
		$("#textCollBlocked").trigger("create");
		
		$("#blockedPlayersList").empty();
		$("#blockedPlayersList").append(newhtml);
		$("#blockedPlayersList").listview("refresh");	
	}
	else{//no blocked players
		
		$("#textCollBlocked").empty();
		$("#textCollBlocked").trigger("create");
		$("#blockedPlayersList").empty();
		$("#blockedPlayersList").listview("refresh");
	}
		
	var newhtml = buildCheckboxComplexIdName(textRitualsSelect, "playerListField", collPlayerArray);
			
	$("#playerList").empty();
	$("#playerList").append(newhtml);
	$("#playerList").trigger( "create" );
			
	//$.mobile.fixedToolbars.show(true);
			
	$("#toast-message").remove();
}