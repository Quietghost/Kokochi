function buildStartPage(currentGlobalScore, totalCollCount){
	
	var level = Math.floor(parseInt(maxGlobalScore)/3)+1;
	
	var flowersHtml = buildFlowersStartPage(totalCollCount);
	
	switch (true){
		case (parseInt(currentGlobalScore) < level):
		$("#background_start").empty();
		var newhtml = '<h2 id="banner-1"></h2>';
		$("#background_start").append(newhtml);
		$("#background_start").append(flowersHtml);
		$("#background_start").trigger("create");
		break;
		
		case (parseInt(currentGlobalScore) >= level && parseInt(currentGlobalScore) <= (level*2)):
		$("#background_start").empty();
		var newhtml = '<h2 id="banner-2"></h2>';
		$("#background_start").append(newhtml);
		$("#background_start").append(flowersHtml);
		$("#background_start").trigger("create");
		
		break;
		case (parseInt(currentGlobalScore) > (level*2) && parseInt(currentGlobalScore) < parseInt(maxGlobalScore)):
		$("#background_start").empty();
		var newhtml = '<h2 id="banner-3"></h2>';
		$("#background_start").append(newhtml);
		$("#background_start").append(flowersHtml);
		$("#background_start").trigger("create");
		
		break;
		case (parseInt(currentGlobalScore) >= parseInt(maxGlobalScore)):
		$("#background_start").empty();
		var newhtml = '<h2 id="banner-4"></h2>';
		$("#background_start").append(newhtml);
		$("#background_start").append(flowersHtml);
		$("#background_start").trigger("create");
		
		break;
	}
	
	var newhtml = "";
	$("#startUsername").empty();
	
	newhtml = '<label for="name"><b>'+textNewUserUsername+':</b></label>'+
           ' <input type="text" name="name" id="name" value=""  />'
			
	$("#startUsername").append(newhtml);
	$("#startUsername").trigger("create");
	
	var newhtml = "";
	$("#startPassword").empty();
	
	newhtml = ' <label for="password"><b>'+textNewUserPassword+':</b></label>'+
            '<input type="password" name="password" id="password" value="" />'
			
	$("#startPassword").append(newhtml);
	$("#startPassword").trigger("create");
	
	
	$("#buttonsStartPage").empty();
	
	var newhtml = '<div class="ui-block-a"><a href="#new_user" data-role="button" data-rel="dialog" data-transition="pop" id="newUserLink">'+buttonStartNewUser+'</a></div>'+
	'<div class="ui-block-b"><button type="submit" data-theme="b" id="login">'+buttonStartLogin+'</button></div>';
	
	$("#buttonsStartPage").append(newhtml);
	$("#buttonsStartPage").trigger("create");
	
	
	if (localStorageLength != 0){
		var meName = localStorage.getItem("meName");
		var mePW = localStorage.getItem("mePW");
		
		$('#name').val(meName);
		if (mePW != "undefined"){
			$('#password').val(mePW);
		}
		
	}else{
	}
	
	
	$("#toast-message").remove();

}