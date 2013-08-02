function buildFlowerPointsScore(myReward){
	
	flowerPoints = parseInt(flowerPoints) + parseInt(myReward);
	
	$("#yourCurrentFlowerPints").empty();
	var htmlYourFlowerPoints = "<center>Your Flower Points: " + flowerPoints +"<center>";
	$("#yourCurrentFlowerPints").append(htmlYourFlowerPoints);
	$("#yourCurrentFlowerPints").trigger( "create" );
	
}