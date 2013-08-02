function buildCheckboxNoEmotion(text, controlgroup){
	
  var newhtml = "<fieldset data-role=\"controlgroup\" id=\""+controlgroup+"\">";
  newhtml +="<legend>"+text+"</legend>" ;
  
  newhtml += "<input type=\"checkbox\" name=\"checkbox-none\" id=\"none\" val=\"emo\" class=\"custom\" />" ;
  newhtml += "<label for=\"none\">"+textBuildCheckboxesNoEmotionsAvailable+"</label>";
  
  newhtml +="</fieldset>";  
  return newhtml;
}

function buildCheckboxComplexIdName(text, controlgroup, list){
	
  var newhtml = "<fieldset data-role=\"controlgroup\" id=\""+controlgroup+"\">";
  newhtml +="<legend>"+text+"</legend>" ;
  //var newhtml = "";
  
  for (var i = 0; i < list.length; i++) {
	 
  		var id = list[i]["id"];
		var name = list[i]["name"];
		var flowerPoints = list[i]["flower_points"];
		
		if ((id != "0") && (name != "0")){
			newhtml += "<input type=\"checkbox\" name=\"playerSelect\" id=\""+id+"\" class=\"custom\" />" ;
			//if (dist != "0"){
				newhtml += "<label for=\""+id+"\">"+name+" (FPs: " + flowerPoints + ")</label>";
			/*}else{
				newhtml += "<label for=\""+id+"\">"+name+" </label>";
			}*/
  		}
  }
  newhtml +="</fieldset>";  
  return newhtml;
}

function buildIndividualCheckboxPlusSelectMenu(controlgroup, id, label, number){
	
  var newhtml = "<fieldset data-role=\"controlgroup\" id=\""+controlgroup+"\">";
  
  newhtml += "<input type=\"checkbox\" name=\"checkbox-"+id+"\" id=\""+id+"\" val=\"emo\" class=\"custom\" />";
  newhtml += "<label for=\""+id+"\">"+label+"</label>";

  newhtml +="</fieldset>";
  
  newhtml +="<label for=\"checkbox_"+id+"\" class=\"select\">"+textBuildCheckboxesChooseEmotionNumber+"</label>";
  newhtml +="<select name=\"select-choice-"+id+"\" id=\"checkbox_"+id+"\">";
  newhtml +="<option value=\"choose-one\" data-placeholder=\"true\">"+textBuildCheckboxesDefaultText+"</option>";
  
  for (i=0;i<=number;i++){
  		newhtml +="<option value=\""+ i + "\">"+i+"</option>" ;
  }
  
  newhtml +="</select>" ;
  
  return newhtml;
}

function buildCheckboxOpenCollaboration(text, controlgroup, list){
	
  var newhtml = "<fieldset data-role=\"controlgroup\" id=\""+controlgroup+"\">";
  newhtml +="<legend>"+text+"</legend>" ;
  
  for (var i = 0; i < list.length; i++) {
	 
  		var id = list[i]["id"];
		var names = list[i]["names"];
		var numbers = list[i]["numbers"];
		
		newhtml += "<input type=\"checkbox\" name=\"checkbox-"+id+"\" id=\"checkbox-"+id+"\" val=\""+id+"\" mode=\"open\" class=\"custom\"/>" ;
		newhtml += "<label for=\"checkbox-"+id+"\">"+id+": "+textBuildCheckboxesOpenCollaborations +names+" (" +numbers+ ")  </label>";
		
  }
  newhtml +="</fieldset>";  
  return newhtml;
}