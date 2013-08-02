function introLeft(){
	if (introImages[0] == "intro_strip_end.jpg"){
		$("#skip").attr("value","Skip Intro");$('#skip').button('refresh');
	}
	var arrayImageTemp = introImages[6];
	var introImageTextTemp = introImageText[6];
	introImages.pop();
	introImageText.pop();
	introImages.unshift(arrayImageTemp);
	introImageText.unshift(introImageTextTemp);
	$("#intro_image").empty();
	$("#intro_image").append('<img src="view/introPages/'+introImages[0]+'" class="comic-image" alt="intro_image">');
	$("#intro_image").trigger( "refresh" );
	$("#image_text").empty();
	$("#image_text").append('<div class="inner">'+introImageText[0]+'</div>');
	$("#image_text").trigger( "refresh" );
	if (introImages[0] == "intro_strip_1.jpg"){
		$('#left').button('disable');
	}else{
		if ($('#right').attr("disabled") == "disabled"){
			$('#right').button('enable');
		}
	}
}

function introRight(){
	var arrayImageTemp = introImages[0];
	var introImageTextTemp = introImageText[0];
	introImages.shift();
	introImageText.shift();
	$("#intro_image").empty();
	$("#intro_image").append('<img src="view/introPages/'+introImages[0]+'" class="comic-image" alt="intro_image">');
	$("#intro_image").trigger( "refresh" );
	$("#image_text").empty();
	$("#image_text").append('<div class="inner">'+introImageText[0]+'</div>');
	$("#image_text").trigger( "refresh" );
	introImages.push(arrayImageTemp);
	introImageText.push(introImageTextTemp);
	if (introImages[0] == "intro_strip_end.jpg"){
		$('#right').button('disable');	
		$("#skip").attr("value","Play");$('#skip').button('refresh');
	}else{
		if ($('#left').attr("disabled") == "disabled"){
			$('#left').button('enable');
		}
	}
}