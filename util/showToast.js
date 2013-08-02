
function showToast(text){
	
	$("#toast-message").remove();
	
	$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><h1>"+ text +"</h1>"+
	"<center><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
		  //.delay( 1500 )
		  //.fadeOut( 400, function(){
			//$(this).remove();
	//});
}

function showCollSuccessful(text){
	$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='coll_success_toast'><h1>"+ text +"</h1>"+
	"<center><input type='button' id='coll_success' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
}

function showCollFailedTemp(text){
	$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='coll_failed_temp_toast'><h1>"+ text +"</h1>"+
	"<center><input type='button' id='coll_pay_flower' value='Yes' style='height: 44px; width: 88px' /><input type='button' id='coll_fail' value='No' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
}

function showCollFailed(text){
	$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='coll_failed_toast'><h1>"+ text +"</h1>"+
	"<center><input type='button' id='coll_fail' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
}

function showActionMessage(text){
	switch (text){
		case "hug":
		$("#toast-message").remove();
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast-message'><center> <img src='view/actions/hugging.png' alt='hug'></p>Please wait...</center>"+
		"</div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
			  .appendTo( $.mobile.pageContainer )
		break;
		case "emotion":
		$("#toast-message").remove();
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast-message'><center> <img src='view/actions/emotion.png' alt='hug'></p>Please wait...</center>"+
		"</div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
			  .appendTo( $.mobile.pageContainer )
		break;
		case "collaboration":
		$("#toast-message").remove();
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast-message'><center> <img src='view/actions/collaboration.png' alt='hug'></p>Please wait...</center>"+
		"</div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
			  .appendTo( $.mobile.pageContainer )
		break;
		case "loading":
		$("#toast-message").remove();
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast-message'><h1>Page loading ...</h1>"+
	"</div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
		break;
		case "login":
		$("#toast-message").remove();
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast-message'><h1>Checking ...</h1>"+
	"</div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
		  .appendTo( $.mobile.pageContainer )
		break;
		
	}
}

function showMessage(text){
	var newhtml = '<div class="ui-bar ui-bar-e" id="message_bar">' +
			'<h3 style="float:left; margin-top:8px;">'+text+'</h3>'+
			'<div style="float:right; margin-top:4px;"><a href="#" data-role="button" data-icon="delete" data-iconpos="notext" id="message_delete">Button</a></div>'+
		'</div>';
	$("#messageField").empty();
	$("#messageField").append(newhtml);
	$("#messageField").trigger( "create" );
		
}

function showTooltip(text){
	switch (text){
		case "emostatement-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast' ><img class='floatLeft' alt='' src='view/img/emotion-sprite.png' />"+ textEmostatementTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "flowerpoints-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/img/flower-points-sprite.png' />"+ textFlowerpointsTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "hug-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/img/hugs-sprite.png' />"+ textHugTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "emostatementcount-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/img/emocount-sprite.png' />"+ textEmostatementcountTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "hugcount-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/img/hugscount-sprite.png' />"+ textHugCountTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "blockedplayer-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/actions/collaboration.png' />"+ textBlockedPlayerTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "tree-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/actions/cherry_tree.png' />"+ textTreeCredits +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		case "coll-tip":
		$("#toast-message").remove();
		$('#toast').remove();
	
		$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='toast'><img class='floatLeft' alt='' src='view/img/collaboration-sprite.png' />"+ textCollTip +""+
		"<center></p><input type='button' id='cancel_toast' value='Ok' style='height: 44px; width: 88px' /></center></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 }).addClass("tooltip")
		  .appendTo( $.mobile.pageContainer )
		break;
		
	}
}