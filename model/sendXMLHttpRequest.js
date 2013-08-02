//////////////////////////////////////////////
function ajaxCall(type, params){
	switch (type){
		case "getEmotions":
			$.ajax( { type: "GET",
				url: 'controller/getEmotions.php',
				cache : false,
				data: params,
				async: true, 
				complete: function(data)	{
					var xml = data.responseXML;
					var allEmotions = xml.getElementsByTagName("emotion");
					
					var allEmotionsArray = new Array();
					
					for (var i = 0; i < allEmotions.length; i++) {
						
						allEmotionsArray[i] = new Object();
						allEmotionsArray[i]["id"] = allEmotions[i].getAttribute("id");
						allEmotionsArray[i]["name"] = allEmotions[i].getAttribute("name");
					}
					buildChargePowerPage(allEmotionsArray);
				}
			});
		break;
		case "getCollPlayersHistory":
			$.ajax( { type: "GET",
				url: 'controller/getCollPlayersHistory.php',
				cache : false,
				data: params,
				async: true, 
				complete: function(data)	{
					collPlayerName = "";
					collPlayerIdString = "";
					collPlayerIdStringSortArray = new Array();
					collPlayerIdStringSortArrayWithoutMe = new Array();
					collPlayerArray = new Array();
					
					var xml = data.responseXML;
					var blockedPlayers = xml.getElementsByTagName("playerBlocked");
					var newhtml="";
					
					blockedPlayerList = new Array();
					blockedPlayerListNames = new Array();
					openCollaborationsArray = new Array();
					
					if (blockedPlayers.length != 0){
											
						for (var i = 0; i < blockedPlayers.length; i++) {
							
							var name = blockedPlayers[i].getAttribute("name");
							
							blockedPlayerList.push(blockedPlayers[i].getAttribute("id"));
							blockedPlayerListNames.push(name);
							
							if (blockedPlayerList.length > 1){
								blockedPlayerList.sort(Numsort);
								blockedPlayerList = removeDuplicates(blockedPlayerList);
							}
							
							if (blockedPlayerListNames.length > 1){
								blockedPlayerListNames.sort();
								blockedPlayerListNames = removeDuplicates(blockedPlayerListNames);
							}
						}
					}
					
					var players = xml.getElementsByTagName("playerAll");
				
					for (var i = 0; i < players.length; i++) {
						var id = players[i].getAttribute("id");
						var name = players[i].getAttribute("name");
						var flowerPoints = players[i].getAttribute("flower_points");
								
						collPlayerArray[i] = new Object();
						collPlayerArray[i]["id"] = id;
						collPlayerArray[i]["name"] = name;
						collPlayerArray[i]["flower_points"] = flowerPoints;
					}
					
					//Mark the blocked players
					for (var i = 0; i < blockedPlayerList.length; i++) {
						for (var j=0; j < collPlayerArray.length; j++){
							if (collPlayerArray[j]["id"] == blockedPlayerList[i]){
								
								collPlayerArray[j]["id"] = "0";
								collPlayerArray[j]["name"] = "0";
								collPlayerArray[j]["flower_points"] = "0";
							}
						}
					}
					buildCollPlayerList(blockedPlayerListNames, collPlayerArray);
				}
			});
		break;
		case "getPlayerRanking":
			$.ajax( { type: "GET",
				url: 'controller/getPlayerRanking.php',
				cache : false,
				async: true, 
				complete: function(data)	{
					var xml = data.responseXML;
			
					var players = xml.getElementsByTagName("player");
					var p_ranking = new Array();
					
					for (var i = 0; i < players.length; i++) {
						
						p_ranking[i] = new Object();
						p_ranking[i]["id"] = players[i].getAttribute("id");
						p_ranking[i]["name"] = players[i].getAttribute("name");
						p_ranking[i]["flower_points"] = players[i].getAttribute("flower_points");
						
					}
					
					buildPlayerRankingList(p_ranking);
				}
			});
		break;
		case "getGlobalScore":
			$.ajax( { type: "GET",
				url: 'controller/getGlobalScore.php',
				cache : false,
				async: true, 
				complete: function(data)	{
					var xml = data.responseXML;
					var globalscore = xml.getElementsByTagName("score");
					
					for (var i = 0; i < globalscore.length; i++) {
						currentscore = globalscore[i].getAttribute("value");
					}
					
					var params = "player_id="+meID;
					ajaxCall("getNumberOfCollForPlayer", params);
					//buildScorePage(currentscore);
				}
			});
		break;
		case "getNumberOfCollForPlayer":
			$.ajax( { type: "GET",
				url: 'controller/getNumberOfCollForPlayer.php',
				cache : false,
				data: params,
				async: true, 
				complete: function(data)	{
					var xml = data.responseXML;
					var numberOfFlowersElement = xml.getElementsByTagName("flowers");
					
					for (var i = 0; i < numberOfFlowersElement.length; i++) {
						number = numberOfFlowersElement[i].getAttribute("number");
					}
					
					buildScorePage(currentscore, number);
				}
			});
		break;
		case "checkGlobalScore":
			$.ajax( { type: "GET",
				url: 'model/checkGlobalScore.php',
				cache : false,
				async: true, 
				complete: function(data)	{
					var statusEnd = "no";
					var xml = data.responseXML;
					var status = xml.getElementsByTagName("status");
			
					for (var i = 0; i < status.length; i++) {
						statusEnd = status[i].getAttribute("end");
					}
					
					if (statusEnd == "no"){
							
						var timestamp = getPostgreSqlTimestampString();
					
						var paramsLogIn = "player_id="+meID+"&timestamp="+timestamp;
						ajaxCall("logIn", paramsLogIn);
						
						$.mobile.changePage('#home-page', { transition: "fade"} );
							
					}else{
						var paramsEndStatus = "player_id="+meID;
						ajaxCall("getEndOfGameStatus", paramsEndStatus);
						buildCredits();
						
						$.mobile.changePage('#end-page', { transition: "fade"} );	
					}
				}
			});
		break;
		case "checkPlayerNameAndPassword":
			$.ajax( { type: "GET",
				url: 'controller/checkPlayerNameAndPassword.php',
				cache : false,
				data: params,
				async: true, 
				/*error: function(xhr, status, errorThrown) { 
            		alert(errorThrown+'\n'+status+'\n'+xhr.statusText); 
				},*/
				complete: function(data, textStatus)	{
					//if (textStatus == "success"){
						
					var myName = $('#name').val();
					var myPassword = $('#password').val();
					
					var xml = data.responseXML;
					var namePassword = xml.getElementsByTagName("status");
					
					for (var i = 0; i < namePassword.length; i++) {
						status = namePassword[i].getAttribute("status");
					}
					
					//login process is finished by a separate javascript file (more difficult to hack, at least a little) 
					if (status == "ok"){
						var userId = 0;
						for (var i = 0; i < namePassword.length; i++) {
							userId = namePassword[i].getAttribute("id");
						}
						
						loginUser(userId, myName, myPassword);
						ajaxCall("checkGlobalScore");
					}else{
						showToast(textStartPW);	
					}
					/*}else{
						alert("error " + textStatus);	
					}*/
				}				
			});
		break;
		case "logIn":
			$.ajax( { type: "GET",
				url: 'controller/setLogInTime.php',
				cache : false,
				data: params,
				async: true, 
				complete: function(data)	{
					
					showActionMessage("loading");
					var timestamp = getPostgreSqlTimestampString();
					var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
					ajaxCall("createPlayerProfile",paramsPlayerInfo);
					
					if (navigator.geolocation) 
					{
						navigator.geolocation.getCurrentPosition( 
							function (position) {  
								var paramsMap = "lat="+ position.coords.latitude+"&lng="+position.coords.longitude+"&playerID=" + meID+ "&emo_table="+emoTable;
								ajaxCall("createTimelineMap",paramsMap);
							},
							function (error)
							{
								var paramsMap = "lat=&lng=&playerID=" + meID+ "&emo_table="+emoTable;
								ajaxCall("createTimelineMap",paramsMap);
								buildLocalizationStatus(false);
							},
							{ enableHighAccuracy: true }
						);
					}else{
						var paramsMap = "lat=&lng=&playerID=" + meID+ "&emo_table="+emoTable;
						ajaxCall("createTimelineMap",paramsMap);
						buildLocalizationStatus(false);
					}
				}/*,
				error: function(xhr, status, errorThrown) { 
            		alert(errorThrown+'\n'+status+'\n'+xhr.statusText); 
				}*/
			});
		break;
		case "createPlayerProfile":
			$.ajax( { type: "GET",
				url: 'controller/createPlayerProfile.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var playerProfile = xml.getElementsByTagName("playerprofile");
					
					for (var i = 0; i < playerProfile.length; i++) {
						
						emocounter = playerProfile[i].getAttribute("emocounter");
						lastemoname = playerProfile[i].getAttribute("lastemoname");
						lastemoid = playerProfile[i].getAttribute("lastemoid");
						hugs = playerProfile[i].getAttribute("hugs");
						flowerPoints = playerProfile[i].getAttribute("flower_points");
						currentHugBase = playerProfile[i].getAttribute("currenthugbase");
						collaborationNumber = playerProfile[i].getAttribute("collaborations");
						
					}
					
					var p_allOtherPlayers = xml.getElementsByTagName("player");
					var p_playerList = new Array();
					
					if (p_allOtherPlayers.length > 0){
						for (var i = 0; i < p_allOtherPlayers.length; i++) {
							
							var p_playerID = p_allOtherPlayers[i].getAttribute("id");
							var p_name = p_allOtherPlayers[i].getAttribute("name");
							var p_lastEmoId = p_allOtherPlayers[i].getAttribute("last_emotion_id");
							var p_lastEmoName = p_allOtherPlayers[i].getAttribute("last_emotion_name");
							var p_lastLogin = p_allOtherPlayers[i].getAttribute("last_login");
							var p_hugs = p_allOtherPlayers[i].getAttribute("hugs");
							var p_flowerPoints = p_allOtherPlayers[i].getAttribute("flower_points");
							
							p_playerList[i] = new Object();
							p_playerList[i]["player_id"] = p_playerID;
							p_playerList[i]["name"] = p_name;
							p_playerList[i]["last_emotion_id"] = p_lastEmoId;
							p_playerList[i]["last_emotion_name"] = p_lastEmoName;
							p_playerList[i]["last_login"] = p_lastLogin;
							p_playerList[i]["hugs"] = p_hugs;
							p_playerList[i]["flower_points"] = p_flowerPoints;
							
						}
					}//Player has no active emotions
					else{
					}
					
					buildPlayerProfile();
					buildPlayerList(p_playerList);
					
					var paramsChargePower = "emo_table=" + emoTable;
					ajaxCall("getEmotions", paramsChargePower);
					
				}
			});
		break;
		case "createUsableEmotionsList":
			$.ajax( { type: "GET",
				url: 'controller/createUsableEmotionsList.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var playerEmotions = xml.getElementsByTagName("emoNamePlayer");
					var oldEmotions = new Array();
					
					if (playerEmotions.length > 0){
						for (var i = 0; i < playerEmotions.length; i++) {
							
							var oldEmotionName = playerEmotions[i].getAttribute("name");
							var oldEmotionNumber = playerEmotions[i].getAttribute("number");
							var oldEmotionId = playerEmotions[i].getAttribute("emoid");
							
							oldEmotions[i] = new Object();
							oldEmotions[i]["id"] = oldEmotionId;
							oldEmotions[i]["name"] = oldEmotionName;
							oldEmotions[i]["number"] = oldEmotionNumber;
							
						}
					}//Player has no active emotions
					else{
					}
					
					buildUsableEmotionsList(oldEmotions);
					
				}
			});
		break;
		case "options":
			$.ajax( { type: "GET",
				url: 'controller/getOptions.php',
				cache : false,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					var xml = data.responseXML;
					var gameOptions = xml.getElementsByTagName("option");
					
					emoTable = "emotions";
					
					for (var i = 0; i < gameOptions.length; i++) {
						
						maxRequiredEmotions = gameOptions[i].getAttribute("max_required_emotions");
						maxGlobalScore = gameOptions[i].getAttribute("max_global_score");
						imageFolder = gameOptions[i].getAttribute("image_folder");
						emoStatementBuffer = gameOptions[i].getAttribute("emo_statement_buffer");
						emoTimeout = gameOptions[i].getAttribute("emo_timeout");
						emoRepeat = gameOptions[i].getAttribute("emo_repeat");
						refreshRate = gameOptions[i].getAttribute("refresh_rate");
						language = gameOptions[i].getAttribute("language");
						currentGlobalScore = gameOptions[i].getAttribute("current_score");
						totalCollCount = gameOptions[i].getAttribute("totalcollcount");
						
						/*sessionStorage.setItem("maxRequiredEmotions", gameOptions[i].getAttribute("max_required_emotions"));
						sessionStorage.setItem("maxGlobalScore", gameOptions[i].getAttribute("max_global_score"));
						sessionStorage.setItem("imageFolder", gameOptions[i].getAttribute("image_folder"));
						sessionStorage.setItem("emoStatementBuffer", gameOptions[i].getAttribute("emo_statement_buffer"));
						sessionStorage.setItem("emoTimeout", gameOptions[i].getAttribute("emo_timeout"));
						sessionStorage.setItem("emoRepeat", gameOptions[i].getAttribute("emo_repeat"));
						sessionStorage.setItem("refreshRate", gameOptions[i].getAttribute("refresh_rate"));
						sessionStorage.setItem("language", gameOptions[i].getAttribute("language"));*/
						
					}
					if (language != "eng"){
						emoTable = emoTable + "_" + language;
					}
					getTextDescriptions();
					buildStartPage(currentGlobalScore, totalCollCount);
					buildCollPageButtons();
				}
			});
		break;
		case "checkCollPlayers":
			$.ajax( { type: "GET",
				url: 'controller/getCollCurrentCount.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					var collPlayerCheck = false;
					var xml = data.responseXML;
					
					var collPlayersTemp = xml.getElementsByTagName("player");
					
					//collPlayersTemp.sort(Numsort);
					if (collPlayersTemp.length > 0) {
						var comparisons = 0;
						
						for(var i = 0; i < collPlayersTemp.length; i++){
						  for(var j = 0; j < collPlayerIdStringSortArray.length; j++){
							if(collPlayersTemp[i].getAttribute("id") == collPlayerIdStringSortArray[j]){
								comparisons++;
								}
							}
						}
						
						if (comparisons == collPlayerIdStringSortArray.length){
							//showToast("all ready");
							
							collPlayerIdStringSortArray.push(meID);
							collPlayerIdStringSortArray.sort(Numsort);
							
							collPlayerIdString = "";
							
							/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
								//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
								var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
								ajaxCall("setCollPlayer",paramsCollPlayer);
							}*/
									
							var requiredEmotionsTimestamp = getPostgreSqlTimestampString();
							
							if (navigator.geolocation) 
							{
								navigator.geolocation.getCurrentPosition( 
									function (position) {  
										currentCoordsLat = position.coords.latitude;
										currentCoordsLong = position.coords.longitude;
										
										var params = "collPlayerIdString="+collPlayerIdStringSortArray.join("~")+"&emo_table="+emoTable+"&timestamp="+requiredEmotionsTimestamp+"&player_id="+meID+"&lat="+currentCoordsLat+"&lng="+currentCoordsLong;
										ajaxCall("doCollaborationSimple",params);
										
										/*var paramsReset = "player_id="+meID+"&tablename=collPlayers";
										ajaxCall("resetOldCollaborations",paramsReset);*/
										
										/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
											//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
											var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
											ajaxCall("setCollPlayer",paramsCollPlayer);
										}*/
							
									},
									function (error)
									{
										
										currentCoordsLat = "";
										currentCoordsLong = "";
										
										var params = "collPlayerIdString="+collPlayerIdStringSortArray.join("~")+"&emo_table="+emoTable+"&timestamp="+requiredEmotionsTimestamp+"&player_id="+meID+"&lat="+currentCoordsLat+"&lng="+currentCoordsLong;
										ajaxCall("doCollaborationSimple",params);
										
										/*var paramsReset = "player_id="+meID+"&tablename=collPlayers";
										ajaxCall("resetOldCollaborations",paramsReset);*/
										
										/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
											//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
											var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
											ajaxCall("setCollPlayer",paramsCollPlayer);
										}*/
										
									},
									{ enableHighAccuracy: true }
								);
							}else{
								currentCoordsLat = "";
								currentCoordsLong = "";
								
								var params = "collPlayerIdString="+collPlayerIdStringSortArray.join("~")+"&emo_table="+emoTable+"&timestamp="+requiredEmotionsTimestamp+"&player_id="+meID+"&lat="+currentCoordsLat+"&lng="+currentCoordsLong;
								ajaxCall("doCollaborationSimple",params);
								
								/*var paramsReset = "player_id="+meID+"&tablename=collPlayers";
								ajaxCall("resetOldCollaborations",paramsReset);*/
								
								/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
									//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
									var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
									ajaxCall("setCollPlayer",paramsCollPlayer);
								}*/
							}
						}
						else{
							showToast(textCollNotReady);
						}
					}
					else{
						showToast(textCollNotReady);
					}
				}
			});
		break;
		case "doCollaborationSimple":
			$.ajax( { type: "GET",
				url: 'controller/doCollaborationSimple.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					var xml = data.responseXML;
					var collaborationResult = xml.getElementsByTagName("collaborationResult");
					
					for (var i = 0; i < collaborationResult.length; i++) {
						
						myReward = collaborationResult[i].getAttribute("myReward");
						numberofrequiredemotions = collaborationResult[i].getAttribute("numberofrequiredemotions");
						namesofrequiredemotions = collaborationResult[i].getAttribute("namesofrequiredemotions");
					}
					
					if (myReward != "-1"){
						
						buildFlowerPointsScore(myReward);
						
						showToast(textCollSuccess1 + myReward + textCollSuccess2 + namesofrequiredemotions + " ("+numberofrequiredemotions+")." + textCollSuccess3);
						
						/*var paramsReset = "player_id="+meID+"&tablename=collPlayers";
						ajaxCall("resetOldCollaborations",paramsReset);*/
						
						/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
							//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
							var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
							ajaxCall("setCollPlayer",paramsCollPlayer);
						}*/
						
						var paramsCollList = "player_id="+meID;
						ajaxCall("getCollPlayersHistory", paramsCollList);
						
						var timestamp = getPostgreSqlTimestampString();
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createPlayerProfile",paramsPlayerInfo);
						
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createUsableEmotionsList", paramsPlayerInfo);
						
					}//If players have to pay flower points in order to make the collaboration successful
					else{
						showToast(textCollFailure1 + namesofrequiredemotions + " ("+numberofrequiredemotions+")" + textCollFailure2);
						
						/*var paramsReset = "player_id="+meID+"&tableName=collPlayers";
						ajaxCall("resetOldCollaborations",paramsReset);*/
						
						/*for (var i=0; i < collPlayerIdStringSortArrayWithoutMe.length; i++){
							//sendXMLHttpRequest("model/setCollPlayer.php?playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i], function(data) {});
							var paramsCollPlayer = "playerID=" + meID + "&collPlayer=" + collPlayerIdStringSortArrayWithoutMe[i];
							ajaxCall("setCollPlayer",paramsCollPlayer);
						}*/
						
						var paramsCollList = "player_id="+meID;
						ajaxCall("getCollPlayersHistory", paramsCollList);
						
						var timestamp = getPostgreSqlTimestampString();
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createPlayerProfile",paramsPlayerInfo);
						
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createUsableEmotionsList", paramsPlayerInfo);
						
					}
				}
			});
		break;
		case "hugPlayer":
			$.ajax( { type: "GET",
				url: 'model/updateHugsByPlayerId.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var hugsLeft = xml.getElementsByTagName("hugsLeft");
					
					var playerInfoName = "";
					
					for (var i = 0; i < hugsLeft.length; i++) {
						hugsForTheDay = hugsLeft[i].getAttribute("hugsLeftForPlayer");
						playerInfoName = hugsLeft[i].getAttribute("hugedPlayerName");
					}
					
					if (hugsForTheDay >= 0){
						showToast(textHugClick_1+playerInfoName+textHugClick_2+"!");
						
						var timestamp = getPostgreSqlTimestampString();
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createPlayerProfileOnly",paramsPlayerInfo);
						
						$('#hugcounter-'+playerHugId).empty();
						$('#hugcounter-'+playerHugId).append('<p id="hugcounter-content-'+playerHugId+'" hug-counter="'+(parseInt(playerHugCount)+1)+'" class="ui-li-aside ui-li-desc"><strong>Huged: </strong>'+(parseInt(playerHugCount)+1)+'</p>');
						$('#hugcounter-'+playerHugId).trigger('create');
						
						$('#fpcounter-'+playerFPId).empty();
						$('#fpcounter-'+playerFPId).append('<span id="fpcounter-content-'+playerFPId+'" fp-counter="'+(parseInt(playerFPCount)+4)+'" class="ui-li-count">'+(parseInt(playerFPCount)+4)+'</span>');
						$('#fpcounter-'+playerFPId).trigger('create');
						
					}
					else{
						showToast(textNoHugsLeft);
						$('input[player-id="'+playerHugId+'"]').button('disable');
						var timestamp = getPostgreSqlTimestampString();
						var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
						ajaxCall("createPlayerProfileOnly",paramsPlayerInfo);
					}
				}
			});
		break;
		case "stateEmotion":
			$.ajax( { type: "GET",
				url: 'controller/stateEmotion.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var statingResult = xml.getElementsByTagName("emotionstatementresult");
					
					//var emotioncounter = 3;
					
					for (var i = 0; i < statingResult.length; i++) {
						resultEmoStating = statingResult[i].getAttribute("result");
						emotioncounter = statingResult[i].getAttribute("emocounter");
						lastEmotionId = statingResult[i].getAttribute("lastemotionid");
					}
					
					switch (resultEmoStating){
						case "new":
							showToast(textEmoStatementCountA);
							/*$('input[emoid="'+lastEmotionId+'"]').button('enable');
							$('input[emoid="'+selectedEmoId+'"]').button('disable');*/
							
							var timestamp = getPostgreSqlTimestampString();
							var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
							ajaxCall("createPlayerProfileOnly",paramsPlayerInfo);
							
							emoStated(selectedName, selectedEmoId)
							
						break;
						case "stated":
							showToast(textEmoStatementCountN_1 + emotioncounter + textEmoStatementCountN_2);
							/*$('input[emoid="'+lastEmotionId+'"]').button('enable');
							$('input[emoid="'+selectedEmoId+'"]').button('disable');*/
							
							var timestamp = getPostgreSqlTimestampString();
							var paramsPlayerInfo = "player_id="+meID+"&emo_table="+emoTable+"&timestamp="+timestamp;
							ajaxCall("createPlayerProfileOnly",paramsPlayerInfo);
							
							emoStated(selectedName, selectedEmoId)
							
						break;
						/*case "same":
							showToast(textEmoStatementOut);
						break;*/
						case "counter_full":
							showToast(textEmoStatementOut);
						break;
					}
				}
			});
		break;
		case "createPlayerProfileOnly":
			$.ajax( { type: "GET",
				url: 'controller/createPlayerProfile.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var playerProfile = xml.getElementsByTagName("playerprofile");
					
					for (var i = 0; i < playerProfile.length; i++) {
						
						emocounter = playerProfile[i].getAttribute("emocounter");
						lastemoname = playerProfile[i].getAttribute("lastemoname");
						lastemoid = playerProfile[i].getAttribute("lastemoid");
						hugs = playerProfile[i].getAttribute("hugs");
						flowerPoints = playerProfile[i].getAttribute("flower_points");
						currentHugBase = playerProfile[i].getAttribute("currenthugbase");
						collaborationNumber = playerProfile[i].getAttribute("collaborations");
						
					}
					
					buildPlayerProfile();
				}
			});
		break;
		case "createTimeline":
			$.ajax( { type: "GET",
				url: 'controller/createTimeline.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var historyEvents = xml.getElementsByTagName("allHistory");
					var pastEvents = new Array();
					
					for (var i = 0; i < historyEvents.length; i++) {
						switch (historyEvents[i].getAttribute("id")){
							case "emo":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["player"] = historyEvents[i].getAttribute("player");
							pastEvents[i]["emotion"] = historyEvents[i].getAttribute("emotion");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							break;
							case "coll":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["collplayers"] = historyEvents[i].getAttribute("collplayers");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							break;
							case "hug":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["sender"] = historyEvents[i].getAttribute("sender");
							pastEvents[i]["reciever"] = historyEvents[i].getAttribute("reciever");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							break;
							
						}
					}
					buildTimeLine(pastEvents);
				}
			});
		break;
		case "createTimelineMap":
			$.ajax( { type: "GET",
				url: 'controller/createTimelineMap.php',
				cache : false,
				async: true, 
				data: params,
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					var historyEvents = xml.getElementsByTagName("allHistory");
					var pastEvents = new Array();
					
					for (var i = 0; i < historyEvents.length; i++) {
						switch (historyEvents[i].getAttribute("id")){
							case "emo":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["player"] = historyEvents[i].getAttribute("player");
							pastEvents[i]["emotion"] = historyEvents[i].getAttribute("emotion");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
							pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
							break;
							case "coll":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["collplayers"] = historyEvents[i].getAttribute("collplayers");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
							pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
							break;
							case "hug":
							pastEvents[i] = new Object();
							pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
							pastEvents[i]["sender"] = historyEvents[i].getAttribute("sender");
							pastEvents[i]["reciever"] = historyEvents[i].getAttribute("reciever");
							pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
							pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
							pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
							break;
							
						}
					}
					
					if (navigator.geolocation)
					{
						navigator.geolocation.getCurrentPosition(
							function (position) {
									buildTimeLineMap(position.coords.latitude,position.coords.longitude, pastEvents);
								},
							function (error)
								{
									buildTimeLineMap(0,0, pastEvents);
								},
							{ enableHighAccuracy: true }
						);
					}else{
						//detectBrowser();
						buildTimeLineMap(pastEvents[0]["lat"],pastEvents[0]["lng"], pastEvents);
					}
					
				}
			});
		break;
		case "createTimelineMapMarkerOnly":
					$.ajax( { type: "GET",
						url: 'controller/createTimelineMap.php',
						cache : false,
						async: true, 
						data: params,
						dataType: "xml", 
						complete: function(data)	{
							
							var xml = data.responseXML;
							var historyEvents = xml.getElementsByTagName("allHistory");
							var pastEvents = new Array();
							
							for (var i = 0; i < historyEvents.length; i++) {
								switch (historyEvents[i].getAttribute("id")){
									case "emo":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["player"] = historyEvents[i].getAttribute("player");
									pastEvents[i]["emotion"] = historyEvents[i].getAttribute("emotion");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "coll":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["collplayers"] = historyEvents[i].getAttribute("collplayers");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "hug":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["sender"] = historyEvents[i].getAttribute("sender");
									pastEvents[i]["reciever"] = historyEvents[i].getAttribute("reciever");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									
								}
							}
							
							if (navigator.geolocation)
							{
								navigator.geolocation.getCurrentPosition(
									function (position) {
											updateMarkers(position.coords.latitude,position.coords.longitude, pastEvents);
										},
									function (error)
										{
											updateMarkers(0,0, pastEvents);
										},
									{ enableHighAccuracy: true }
								);
							}else{
								
								if (pastEvents.length > 0){
									updateMarkers(pastEvents[0]["lat"],pastEvents[0]["lng"], pastEvents);
								}
								else{
									updateMarkers(0,0, pastEvents);
								}
							}
						}
					});
		break;
		case "createTimelineMapMarkerOnlyAll":
					$.ajax( { type: "GET",
						url: 'controller/createTimelineMapAll.php',
						cache : false,
						async: true, 
						data: params,
						dataType: "xml", 
						complete: function(data)	{
							
							var xml = data.responseXML;
							var historyEvents = xml.getElementsByTagName("allHistory");
							var pastEvents = new Array();
							
							for (var i = 0; i < historyEvents.length; i++) {
								switch (historyEvents[i].getAttribute("id")){
									case "emo":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["player"] = historyEvents[i].getAttribute("player");
									pastEvents[i]["emotion"] = historyEvents[i].getAttribute("emotion");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "coll":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["collplayers"] = historyEvents[i].getAttribute("collplayers");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "hug":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["sender"] = historyEvents[i].getAttribute("sender");
									pastEvents[i]["reciever"] = historyEvents[i].getAttribute("reciever");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									
								}
							}
							
							if (navigator.geolocation)
							{
								navigator.geolocation.getCurrentPosition(
									function (position) {
											updateMarkers(position.coords.latitude,position.coords.longitude, pastEvents);
										},
									function (error)
										{
											updateMarkers(pastEvents[0]["lat"],pastEvents[0]["lng"], pastEvents);
										},
									{ enableHighAccuracy: true }
								);
							}else{
								//detectBrowser();
								updateMarkers(pastEvents[0]["lat"],pastEvents[0]["lng"], pastEvents);
							}
						}
					});
		break;
		case "createPlayerListOnly":
			$.ajax( { type: "GET",
				url: 'controller/createPlayerProfile.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					
					var p_allOtherPlayers = xml.getElementsByTagName("player");
					var p_playerList = new Array();
					
					if (p_allOtherPlayers.length > 0){
						for (var i = 0; i < p_allOtherPlayers.length; i++) {
							
							var p_playerID = p_allOtherPlayers[i].getAttribute("id");
							var p_name = p_allOtherPlayers[i].getAttribute("name");
							var p_lastEmoId = p_allOtherPlayers[i].getAttribute("last_emotion_id");
							var p_lastEmoName = p_allOtherPlayers[i].getAttribute("last_emotion_name");
							var p_lastLogin = p_allOtherPlayers[i].getAttribute("last_login");
							var p_hugs = p_allOtherPlayers[i].getAttribute("hugs");
							var p_flowerPoints = p_allOtherPlayers[i].getAttribute("flower_points");
							
							p_playerList[i] = new Object();
							p_playerList[i]["player_id"] = p_playerID;
							p_playerList[i]["name"] = p_name;
							p_playerList[i]["last_emotion_id"] = p_lastEmoId;
							p_playerList[i]["last_emotion_name"] = p_lastEmoName;
							p_playerList[i]["last_login"] = p_lastLogin;
							p_playerList[i]["hugs"] = p_hugs;
							p_playerList[i]["flower_points"] = p_flowerPoints;
							
						}
					}//Player has no active emotions
					else{
					}
					buildPlayerList(p_playerList);
				}
			});
		break;
		case "clearOldCollaborationsTable":
			$.ajax( { type: "GET",
				url: 'model/clearOldCollaborationsTable.php',
				cache : false,
				async: true,
				data: params
			});
		break;
		/*case "resetOldCollaborations":
			$.ajax( { type: "GET",
				url: 'model/resetCollPlayersByPlayerID.php',
				cache : false,
				async: true,
				data: params
			});
		break;*/
		case "getEndOfGameStatus":
			$.ajax( { type: "GET",
				url: 'controller/getEndOfGameStatus.php',
				cache : false,
				data: params,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var xml = data.responseXML;
					
					var endGameStatus = xml.getElementsByTagName("endofgamestatus");
					
					var endOfGameStatus = new Array();
					
					for (var i = 0; i < endGameStatus.length; i++) {
							
						endOfGameStatus[i] = new Object();
						endOfGameStatus[i]["my_flower_points"] = endGameStatus[i].getAttribute("my_flower_points");
						endOfGameStatus[i]["my_ranking"] = endGameStatus[i].getAttribute("my_ranking");
						endOfGameStatus[i]["top_player"] = endGameStatus[i].getAttribute("top_player");
						endOfGameStatus[i]["top_player_flower_points"] = endGameStatus[i].getAttribute("top_player_flower_points");
						endOfGameStatus[i]["number_of_emotions"] = endGameStatus[i].getAttribute("number_of_emotions");
						endOfGameStatus[i]["number_of_collaborations"] = endGameStatus[i].getAttribute("number_of_collaborations");
						endOfGameStatus[i]["number_of_hugs_send"] = endGameStatus[i].getAttribute("number_of_hugs_send");
						endOfGameStatus[i]["number_of_hugs_recieved"] = endGameStatus[i].getAttribute("number_of_hugs_recieved");
							
					}
					
					buildEndPage(endOfGameStatus);
				}
			});
		break;
		case "checkUserNameAndPW":
			$.ajax( { type: "GET",
				url: 'controller/getAllPlayers.php',
				cache : false,
				async: true, 
				dataType: "xml", 
				complete: function(data)	{
					
					var namecheck = true;
					
					var newName = $('#newName').val();
					var xml = data.responseXML;
					
					var players = xml.getElementsByTagName("player");
					
					for (var i = 0; i < players.length; i++) {
						var name = players[i].getAttribute("name");
						if (name == ($('#newName').val())){
							namecheck = false;
						}
					}
					if (namecheck){
						if ($('#newPassword').val() == $('#newPassword2').val()){
							//If it is a unique name, create a new player with the given name and password
							//Change back to the start page
							var paramsNewPlayer = "player_name="+$('#newName').val()+"&player_password="+
							$('#newPassword').val();
							
							ajaxCall("createNewPlayer",paramsNewPlayer);
						
							newUser = true;
							
							$('#name').val(newName);
							$('#password').val($('#newPassword').val());
							
							$.mobile.changePage('#start', { transition: "fade"} );
						}else{
							$('#newPassword').val("");
							$('#newPassword2').val("");
							showToast(textNewPwMatch);
						}
					}else{
						$('#newName').val("");
						$('#newPassword').val("");
						$('#newPassword2').val("");
						showToast(textNewUsernameTaken);
					}
				}
			});
		break;
		case "createNewPlayer":
			$.ajax( { type: "GET",
				url: 'model/createNewPlayer.php',
				async: true,
				data: params
			});
		break;
		case "setCollCurrent":
			$.ajax( { type: "GET",
				url: 'model/setCollCurrent.php',
				cache : false,
				async: true,
				data: params
			});
		break;
		/*case "setCollPlayer":
			$.ajax( { type: "GET",
				url: 'model/setCollPlayer.php',
				cache : false,
				async: true,
				data: params
			});
		break;*/
		case "resetCollPlayersByPlayerIdAndOtherPlayer":
			$.ajax( { type: "GET",
				url: 'model/resetCollPlayersByPlayerIdAndOtherPlayer.php',
				cache : false,
				async: true,
				data: params
			});
		break;
		case "clearTable":
			$.ajax( { type: "GET",
				url: 'model/clearTable.php',
				cache : false,
				async: true,
				data: params
			});
		break;
		case "createTimelineMapMarkerOnlyAllAnalysis":
					$.ajax( { type: "GET",
						url: 'controller/createTimelineMapAllAnalysis.php',
						cache : false,
						async: true, 
						data: params,
						dataType: "xml", 
						complete: function(data)	{
							
							var xml = data.responseXML;
							var historyEvents = xml.getElementsByTagName("allHistory");
							var pastEvents = new Array();
							
							for (var i = 0; i < historyEvents.length; i++) {
								switch (historyEvents[i].getAttribute("id")){
									case "emo":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["player"] = historyEvents[i].getAttribute("player");
									pastEvents[i]["emotion"] = historyEvents[i].getAttribute("emotion");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "coll":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["collplayers"] = historyEvents[i].getAttribute("collplayers");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									case "hug":
									pastEvents[i] = new Object();
									pastEvents[i]["id"] = historyEvents[i].getAttribute("id");
									pastEvents[i]["sender"] = historyEvents[i].getAttribute("sender");
									pastEvents[i]["reciever"] = historyEvents[i].getAttribute("reciever");
									pastEvents[i]["timestamp"] = historyEvents[i].getAttribute("timestamp");
									pastEvents[i]["lng"] = historyEvents[i].getAttribute("lng");
									pastEvents[i]["lat"] = historyEvents[i].getAttribute("lat");
									break;
									
								}
							}
							
							updateMarkers2(lat,lng, pastEvents);
						}
					});
		break;
		case "createGameStatistics":
					$.ajax( { type: "GET",
						url: 'controller/createGameStatistics.php',
						cache : false,
						async: true, 
						data: params,
						dataType: "xml", 
						complete: function(data)	{
							
							var xml = data.responseXML;
							var gameStatistics = xml.getElementsByTagName("gamestats");
							var gamestats = new Array();
							
							for (var i = 0; i < gameStatistics.length; i++) {
								gamestats[i] = new Object();
								gamestats[i]["all_players"] = gameStatistics[i].getAttribute("all_players");
								gamestats[i]["active_players"] = gameStatistics[i].getAttribute("active_players");
								gamestats[i]["number_of_emotions"] = gameStatistics[i].getAttribute("number_of_emotions");
								gamestats[i]["number_of_positive_emotions"] = gameStatistics[i].getAttribute("number_of_positive_emotions");
								gamestats[i]["number_of_negative_emotions"] = gameStatistics[i].getAttribute("number_of_negative_emotions");
								gamestats[i]["number_of_collaborations"] = gameStatistics[i].getAttribute("number_of_collaborations");
								gamestats[i]["number_of_hugs_send"] = gameStatistics[i].getAttribute("number_of_hugs_send");
								gamestats[i]["number_of_days_played"] = gameStatistics[i].getAttribute("number_of_days_played");
								gamestats[i]["number_of_coll_players"] = gameStatistics[i].getAttribute("number_of_coll_players");
								
							}
							
							//buildGamestats
							buildGamestats(gamestats);
							
							var timeline = xml.getElementsByTagName("timeline");
							var timelineArray = new Array();
							
							for (var i = 0; i < timeline.length; i++) {
								timelineArray[i] = new Object();
								timelineArray[i]["day"] = timeline[i].getAttribute("day");
								timelineArray[i]["total"] = timeline[i].getAttribute("total");
							}
							
							//buildTimeline
							buildTimeline(timelineArray);
							
							var playerstats = xml.getElementsByTagName("playerstats");
							var players = new Array();
							
							for (var i = 0; i < playerstats.length; i++) {
								players[i] = new Object();
								players[i]["id"] = playerstats[i].getAttribute("id");
								players[i]["name"] = playerstats[i].getAttribute("name");
								players[i]["my_flower_points"] = playerstats[i].getAttribute("my_flower_points");
								players[i]["number_of_emotions"] = playerstats[i].getAttribute("number_of_emotions");
								players[i]["number_of_collaborations"] = playerstats[i].getAttribute("number_of_collaborations");
								players[i]["number_of_hugs_send"] = playerstats[i].getAttribute("number_of_hugs_send");
								players[i]["number_of_hugs_recieved"] = playerstats[i].getAttribute("number_of_hugs_recieved");
							}
							
							//buildPlayerstats
							buildPlayerstats(players);
							
							var emotionstats = xml.getElementsByTagName("emotionstats");
							var emotions = new Array();
							
							for (var i = 0; i < emotionstats.length; i++) {
								emotions[i] = new Object();
								emotions[i]["name"] = emotionstats[i].getAttribute("name");
								emotions[i]["count"] = emotionstats[i].getAttribute("count");
								emotions[i]["type"] = emotionstats[i].getAttribute("type");
							}
							
							//buildPlayerstats
							buildEmotionstats(emotions);
							
							
							
							var collTimeline = xml.getElementsByTagName("coll_timeline");
							var collTimelineArray = new Array();
							
							for (var i = 0; i < collTimeline.length; i++) {
								collTimelineArray[i] = new Object();
								collTimelineArray[i]["day"] = collTimeline[i].getAttribute("day");
								collTimelineArray[i]["total"] = collTimeline[i].getAttribute("total");
							}
							
							//buildTimeline
							buildCollTimeline(collTimelineArray);
							
							var hugTimeline = xml.getElementsByTagName("hug_timeline");
							var hugTimelineArray = new Array();
							
							for (var i = 0; i < hugTimeline.length; i++) {
								hugTimelineArray[i] = new Object();
								hugTimelineArray[i]["day"] = hugTimeline[i].getAttribute("day");
								hugTimelineArray[i]["total"] = hugTimeline[i].getAttribute("total");
							}
							
							//buildTimeline
							buildHugTimeline(hugTimelineArray);
						}
					});
		break;
		case "null":
		break;
	}
}
//////////////////////////////////////////////
function sendXMLHttpRequest(url,callback) {
	 var request = window.ActiveXObject ?
		 new ActiveXObject('Microsoft.XMLHTTP') :
		 new XMLHttpRequest;
	
	 request.onreadystatechange = function() {
	   if (request.readyState == 4) {
		 request.onreadystatechange = doNothing;
		 callback(request, request.status);
	   }
	 };
	
	 request.open('GET', url, true);
	 request.send(null);
}

function doNothing() {}

