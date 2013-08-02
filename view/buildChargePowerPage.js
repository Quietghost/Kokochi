function buildChargePowerPage(allEmotionsArray){
	
	var newhtml = "";
	
	$("#chargePowerSelect").empty();
	
	/*sendXMLHttpRequest("controller/getEmotions.php?emo_table=" + emoTable, function(data) {
		
		var xml = data.responseXML;
		var allEmotions = xml.getElementsByTagName("emotion");*/
			
			var headline1 = allEmotionsArray[1]["name"].replace(" ","");
			
			newhtml =  '<div data-role="collapsible" data-theme="b" data-content-theme="d">'+
							'<h3>'+textChargePowerPage+'</h3>'+
							'<p></p>'+
							'<div data-role="collapsible" data-collapsed="true">'+
								'<h3>'+headline1+'...</h3>'+
									'<p>'+
										'<fieldset class="ui-grid-a">';
			
			var j=1;
			for (var i = 0; i < 7; i++) {
								
				var id = allEmotionsArray[i]["id"];
				var name = allEmotionsArray[i]["name"];
				
				if (j == 1){
					newhtml += '<div class="ui-block-a" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j++;
				}else{
					newhtml += '<div class="ui-block-b" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j=1;
				}
									
			}
			
			newhtml += '</fieldset>' +
					'</p>'+
				'</div>';
			
			var headline2 = allEmotionsArray[12]["name"].replace(" ","");
			
			newhtml += '<div data-role="collapsible" data-collapsed="true">'+
						'<h3>'+headline2+'...</h3>'+
            				'<p>'+
								'<fieldset class="ui-grid-a">';
								
			j=1;
			for (var i = 7; i < 15; i++) {
										
				var id = allEmotionsArray[i]["id"];
				var name = allEmotionsArray[i]["name"];
	
				if (j == 1){
					newhtml += '<div class="ui-block-a" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j++;
				}else{
					newhtml += '<div class="ui-block-b" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j=1;
				}					
			}
			
			newhtml += '</fieldset>' +
					'</p>'+
				'</div>';
	
			var headline3 = allEmotionsArray[18]["name"].replace(" ","");
			
			newhtml += '<div data-role="collapsible" data-collapsed="true">'+
						'<h3>'+headline3+'...</h3>'+
            				'<p>'+
								'<fieldset class="ui-grid-a">';
			
			j=1;
			for (var i = 15; i < 21; i++) {
										
				var id = allEmotionsArray[i]["id"];
				var name = allEmotionsArray[i]["name"];
	
				if (j == 1){
					newhtml += '<div class="ui-block-a" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j++;
				}else{
					newhtml += '<div class="ui-block-b" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j=1;
				}					
			}
			
			newhtml += '</fieldset>' +
					'</p>'+
				'</div>';
	
			var headline4 = allEmotionsArray[23]["name"].replace(" ","");
			
			newhtml += '<div data-role="collapsible" data-collapsed="true">'+
						'<h3>'+headline4+'...</h3>'+
            				'<p>'+
								'<fieldset class="ui-grid-a">';
			j=1;
			for (var i = 21; i < 27; i++) {
										
				var id = allEmotionsArray[i]["id"];
				var name = allEmotionsArray[i]["name"];
	
				if (j == 1){
					newhtml += '<div class="ui-block-a" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j++;
				}else{
					newhtml += '<div class="ui-block-b" id="div-'+id+'"><input type="button" value="'+name+'" id="button" emoid="'+id+'" /></div>';
					j=1;
				}					
			}
			
			newhtml += '</fieldset>' +
					'</p>'+
				'</div>'+
			'</div>';
	
			
			$("#chargePowerSelect").append(newhtml);
			$("#chargePowerSelect").trigger( "create" );
			
			/*if (lastemoid != "0"){
				$('input[emoid="'+lastemoid+'"]').button('disable');
			}*/
			
	//});
	
	//$.mobile.fixedToolbars.show(true);
	
	$("#toast-message").remove();
}