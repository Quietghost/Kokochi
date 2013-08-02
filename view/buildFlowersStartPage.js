function buildFlowersStartPage(number){
	var newhtml ="";
	newhtml += '<center>';
	
	var baseTop = 380;
	
	for (i=0; i < number;i++){
		var side = Math.floor(Math.random()*51);
		var horizontalPosition = Math.floor(Math.random()*50);
		var verticalPosition = Math.floor(Math.random()*15);
		//var newBaseTop = baseTop + verticalPosition;
		
		if (side > 50){
			var newBaseTop = baseTop + verticalPosition;
			newhtml += '<img src="view/img/flower_small.png" alt="flower" style="position:relative; top:' + eval(newBaseTop) + 'px; left:'+ eval(horizontalPosition) +'px">';
		}
		else{
			var newBaseTop = baseTop - verticalPosition;
			newhtml += '<img src="view/img/flower_small.png" alt="flower" style="position:relative; top:' + eval(newBaseTop) + 'px; right:'+ eval(horizontalPosition) +'px">';
		}
	}
	newhtml += '<center>';
	
	return newhtml;
	
}