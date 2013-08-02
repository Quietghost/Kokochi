function loginUser(userId, myName, myPassword){
	
	sessionStorage.setItem("loginstatus", "ok");
	sessionStorage.setItem("meID", userId);
	sessionStorage.setItem("meName", myName);
	
	localStorage.setItem("meName", myName);
	localStorage.setItem("mePW", myPassword);
	
	meID = userId;
	meName = myName;
	
	$("#playerName").empty();
	$("#playerName").append(myName);
	$("#playerName").trigger("create");
	
}