function buildTimeLineMap(lat,lng, pastEvents){
	
	if (lat != 0){
		center = new google.maps.LatLng(lat,lng);
	}
	
	map.setCenter(center, 16);
	
	var infoWindow = new google.maps.InfoWindow;
	
	/*google.maps.event.addListener(marker, 'click', function() {
  		infoWindow.setContent(marker.content);
      	infoWindow.open(map, marker);
   	});*/
	
	if (lat != 0){
		var point = new google.maps.LatLng(
					  lat,
					  lng);
		var html = "My current location! (" + lat + "/" + lng +")";
		
		var marker1 = new MarkerWithLabel({
				position: point,
				map: map,
				animation: google.maps.Animation.DROP,
				icon: 'view/myIcons/star-3.png',
				content: html,
				labelContent: "Me",
				labelAnchor: new google.maps.Point(22, 0),
				labelClass: "map-labels-me", // the CSS class for the label
				labelStyle: {opacity: 0.75}
			});
					
		bindInfoWindow(marker1, map, infoWindow, html);
		markersArray.push(marker1);
		
	}
	for (var i = 0; i < pastEvents.length; i++) {
		switch (pastEvents[i]["id"]){
			
			case "emo":
			if (pastEvents[i]["lat"] != "0"){
				var point = new google.maps.LatLng(
				  pastEvents[i]["lat"],
				  pastEvents[i]["lng"]);
				var html = "" + pastEvents[i]["player"] + " " + textTimelineEmotionAction + " "+ pastEvents[i]["emotion"] + " " + pastEvents[i]["timestamp"];
				var marker = new MarkerWithLabel({
				   position: point,
				   draggable: false,
				   map: map,
				   animation: google.maps.Animation.DROP,
				   icon: 'view/myIcons/comment-map-icon.png',
				   content: html,
				   labelContent: pastEvents[i]["emotion"],
				   labelAnchor: new google.maps.Point(22, 0),
				   labelClass: "map-labels-emo", // the CSS class for the label
				   labelStyle: {opacity: 0.75}
				});
				
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray.push(marker);
			}
			break;
			case "coll":
			if (pastEvents[i]["lat"] != "0"){
				var point = new google.maps.LatLng(
				  pastEvents[i]["lat"],
				  pastEvents[i]["lng"]);
				var html = "" + pastEvents[i]["collplayers"] + " "+textTimelineRitualAction+" (" + pastEvents[i]["timestamp"] + ")";
				var marker = new MarkerWithLabel({
				   position: point,
				   draggable: false,
				   map: map,
				   animation: google.maps.Animation.DROP,
				   icon: 'view/myIcons/regroup.png',
				   content: html,
				   labelContent: pastEvents[i]["collplayers"],
				   labelAnchor: new google.maps.Point(22, 0),
				   labelClass: "map-labels-coll", // the CSS class for the label
				   labelStyle: {opacity: 0.75}
				});
				
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray.push(marker);
			}
			break;
			case "hug":
			if (pastEvents[i]["lat"] != "0"){
				var point = new google.maps.LatLng(
				  pastEvents[i]["lat"],
				  pastEvents[i]["lng"]);
				var html = "" + pastEvents[i]["sender"] + " "+textTimelineHugAction+" " + pastEvents[i]["reciever"];
				var marker = new MarkerWithLabel({
				   position: point,
				   draggable: false,
				   map: map,
				   animation: google.maps.Animation.DROP,
				   icon: 'view/myIcons/letter_h.png',
				   content: html,
				   labelContent: pastEvents[i]["sender"],
				   labelAnchor: new google.maps.Point(22, 0),
				   labelClass: "map-labels-hug", // the CSS class for the label
				   labelStyle: {opacity: 0.75}
				});
				
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray.push(marker);
			}
			break;
		}
	}
}

function updateMarkers(lat,lng, pastEvents) {
	
  	var infoWindow = new google.maps.InfoWindow;
  
	/*google.maps.event.addListener(marker, 'click', function() {
  		infoWindow.setContent(marker.content);
      	infoWindow.open(map, marker);
   	});*/
  	
	if (lat != 0){
				
		var point = new google.maps.LatLng(
					  lat,
					  lng);
		var html = "My current location! (" + lat + "/" + lng +")";
		
		var marker1 = new MarkerWithLabel({
					position: point,
					map: map,
					animation: google.maps.Animation.DROP,
					icon: 'view/myIcons/star-3.png',
					content: html,
					labelContent: "Me",
					labelAnchor: new google.maps.Point(22, 0),
					labelClass: "map-labels-me", // the CSS class for the label
					labelStyle: {opacity: 0.75}
			});
					
		bindInfoWindow(marker1, map, infoWindow, html);
		
		markersArray.push(marker1);
	}
	else{
		
	}
	
  	for (var i = 0; i < pastEvents.length; i++) {
  		switch (pastEvents[i]["id"]){
  			
  			case "emo":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["player"] + " " + textTimelineEmotionAction + " "+ pastEvents[i]["emotion"] + " " + pastEvents[i]["timestamp"];
  				var marker = new MarkerWithLabel({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/comment-map-icon.png',
				   content: html,
  				   labelContent: pastEvents[i]["emotion"],
  				   labelAnchor: new google.maps.Point(22, 0),
  				   labelClass: "map-labels-emo", // the CSS class for the label
  				   labelStyle: {opacity: 0.75}
  				});
  				
  				bindInfoWindow(marker, map, infoWindow, html);
  				markersArray.push(marker);
  			}
  			break;
  			case "coll":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["collplayers"] + " "+textTimelineRitualAction+" (" + pastEvents[i]["timestamp"] + ")";
  				var marker = new MarkerWithLabel({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/regroup.png',
				   content: html,
  				   labelContent: pastEvents[i]["collplayers"],
  				   labelAnchor: new google.maps.Point(22, 0),
  				   labelClass: "map-labels-coll", // the CSS class for the label
  				   labelStyle: {opacity: 0.75}
  				});
  				
  				bindInfoWindow(marker, map, infoWindow, html);
  				markersArray.push(marker);
  			}
  			break;
  			case "hug":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["sender"] + " "+textTimelineHugAction+" " + pastEvents[i]["reciever"];
  				var marker = new MarkerWithLabel({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/letter_h.png',
				   content: html,
  				   labelContent: pastEvents[i]["sender"],
  				   labelAnchor: new google.maps.Point(22, 0),
  				   labelClass: "map-labels-hug", // the CSS class for the label
  				   labelStyle: {opacity: 0.75}
  				});
  				
  				bindInfoWindow(marker, map, infoWindow, html);
  				markersArray.push(marker);
  			}
  			break;
  		}
  		
	}
	
	
	if (pastEvents.length > 0){
		center = new google.maps.LatLng(
	  	lat,
  		lng);
	
		map.setCenter(center);
		map.setZoom(16);
	}
	else{
		center = new google.maps.LatLng(
	  	0,
  		0);
	
		map.setCenter(center);
		map.setZoom(2);
	}
	
	$("#toast-message").remove();
}

function bindInfoWindow(marker, map, infoWindow, html) {
	
	google.maps.event.addListener(marker, 'click', function() {
        	infoWindow.setContent(html);
        	infoWindow.open(map, marker);
      	});
}

// Deletes all markers in the array by removing references to them
function deleteOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
    markersArray.length = 0;
  }
}

function deleteOverlays2() {

  var markers = oms.getMarkers();

  if (markers) {
     for (i in markers) {
         markers[i].setMap(null);
     }
     markers.length = 0;
  }
  oms.clearMarkers();
}


//-------------------------------------------------------------------------------------

function updateMarkers2(lat,lng, pastEvents) {
  	infoWindow = new google.maps.InfoWindow;
  
  	oms.addListener('click', function(marker) {
		infoWindow.setContent(marker.content);
		infoWindow.open(map, marker);
	});
  
  	for (var i = 0; i < pastEvents.length; i++) {
  		switch (pastEvents[i]["id"]){
  			
  			case "emo":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["player"] + " " + textTimelineEmotionAction + " "+ pastEvents[i]["emotion"] + " " + pastEvents[i]["timestamp"];
  				var marker = new google.maps.Marker({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/comment-map-icon.png',
				   content: html
  				});
  				
				//setTimeout(function() {
					oms.addMarker(marker);
				//}, i * 200);

  			}
  			break;
  			case "coll":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["collplayers"] + " "+textTimelineRitualAction+" (" + pastEvents[i]["timestamp"] + ")";
  				var marker = new google.maps.Marker({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/regroup.png',
				   content: html
  				});
  				
  				
				//setTimeout(function() {
					oms.addMarker(marker);
				//}, i * 200);
  			}
  			break;
  			case "hug":
  			if (pastEvents[i]["lat"] != "0"){
  				var point = new google.maps.LatLng(
  				  pastEvents[i]["lat"],
  				  pastEvents[i]["lng"]);
  				var html = "" + pastEvents[i]["sender"] + " "+textTimelineHugAction+" " + pastEvents[i]["reciever"];
  				var marker = new google.maps.Marker({
  				   position: point,
  				   draggable: false,
  				   map: map,
				   animation: google.maps.Animation.DROP,
  				   icon: 'view/myIcons/letter_h.png',
				   content: html
  				});
  				
				//setTimeout(function() {
					oms.addMarker(marker);
				//}, i * 200);
				
  			}
  			break;
  		}
  		
	}
	
	
	if (pastEvents.length > 0){
		center = new google.maps.LatLng(
	  	lat,
  		lng);
	
		map.setCenter(center);
		map.setZoom(16);
	}
	else{
		center = new google.maps.LatLng(
	  	0,
  		0);
	
		map.setCenter(center);
		map.setZoom(2);
	}
	
	$.mobile.hidePageLoadingMsg();
}
  