function buildNewUserPage(){

	var newhtml = "";
	$("#newUserPageWhole").empty();
	
	newhtml = '<div data-role="fieldcontain">'+
            '<label for="newName"><b>'+textNewUserUsername+':</b></label>'+
            '<input type="text" name="newName" id="newName" value=""  />'+
        '</div>	'+
       ' <div data-role="fieldcontain">'+
            '<label for="newPassword"><b>'+textNewUserPassword+':</b></label>'+
            '<input type="password" name="newPassword" id="newPassword" value="" />'+
        '</div>'+
       ' <div data-role="fieldcontain">'+
           ' <label for="newPassword2"><b>'+textNewUserPassword+':</b></label>'+
            '<input type="password" name="newPassword2" id="newPassword2" value="" />'+
       ' </div>'+
       ' <fieldset class="ui-grid-a">'+
           ' <div class="ui-block-a"><button type="submit" data-theme="c" id="cancel">'+buttonNewUserCancel+'</button></div>'+
           ' <div class="ui-block-b"><button type="submit" data-theme="b" id="addNewUser">'+buttonNewUserSubmit+'</button></div>	'+
       ' </fieldset>';
	
		$("#newUserPageWhole").append(newhtml);
		$("#newUserPageWhole").trigger("create");
}