function getPostgreSqlTimestampString(){
	var d = new Date();
	var date = formatDate(d, "YYYY-MM-DD");
	//alert(date);
	
	var t = d.getTime();
	var time = formatTime(d, "HH:MM:SS");
	//alert(time);
	
	var diff = "" + checkTimeZone();
	if (diff.length==1){
		diff = "0" + diff;
	}
	//alert(diff);
	
	var timestamp = date + "~" + time + "~" + diff;
	
	return timestamp;
}


function formatDate(dateValue, format) {
	
	var fmt = format.toUpperCase(); 
    var re = /^(M|MM|D|DD|YYYY)([\-\/]{1})(M|MM|D|DD|YYYY)(\2)(M|MM|D|DD|YYYY)$/; 
	
    if (!re.test(fmt)) { fmt = "MM/DD/YYYY"; } 
    if (fmt.indexOf("M") == -1) { fmt = "MM/DD/YYYY"; } 
    if (fmt.indexOf("D") == -1) { fmt = "MM/DD/YYYY"; } 
    if (fmt.indexOf("YYYY") == -1) { fmt = "MM/DD/YYYY"; } 
	var M = "" + (dateValue.getMonth()+1); 
    var MM = "0" + M; 
	
    MM = MM.substring(MM.length-2, MM.length); 
    var D = "" + (dateValue.getDate()); 
    var DD = "0" + D; 
	
    DD = DD.substring(DD.length-2, DD.length); 
    var YYYY = "" + (dateValue.getFullYear()); 
	var sep = "/"; 
    
	if (fmt.indexOf("-") != -1) { 
		sep = "-"; 
	} 
    
	var pieces = fmt.split(sep); 
    var result = ""; 
	
	switch (pieces[0]) { 
         case "M" : result += M + sep; break; 
         case "MM" : result += MM + sep; break; 
         case "D" : result += D + sep; break; 
         case "DD" : result += DD + sep; break; 
         case "YYYY" : result += YYYY + sep; break; 
    } 
	switch (pieces[1]) { 
         case "M" : result += M + sep; break; 
         case "MM" : result += MM + sep; break; 
         case "D" : result += D + sep; break; 
         case "DD" : result += DD + sep; break; 
         case "YYYY" : result += YYYY + sep; break; 
    } 
    switch (pieces[2]) { 
         case "M" : result += M; break; 
         case "MM" : result += MM; break; 
         case "D" : result += D; break; 
         case "DD" : result += DD; break; 
         case "YYYY" : result += YYYY; break; 
    } 
	return result; 
} 

function formatTime(timeValue, format) { 
	var fmt = format.toUpperCase(); 
    var re = /^(H|HH)(:MM)(:SS)?( AM)?$/; 
	
    if (!re.test(fmt)) { fmt = "H:MM AM"; } 
	
	var MM = "0" + (timeValue.getMinutes()); 
    MM = MM.substring(MM.length-2, MM.length); 
    var SS = "0" + (timeValue.getSeconds()); 
    SS = SS.substring(SS.length-2, SS.length); 
    var H = "" + (timeValue.getHours()); 
    var HH = "0" + H; 
    HH = HH.substring(HH.length-2, HH.length); 
	var meridian = ""; 
	
    if (fmt.indexOf(" AM") != -1) { 
         meridian = "AM"; 
         if (HH == "00") { HH = "12"; } 
         if (HH == "12") { meridian = "PM"; } 
         if (parseInt(HH, 10) > 12) {
              meridian = "PM"; 
              var hrs = (parseInt(HH, 10)-12); 
              H = "" + hrs; 
              HH = "0" + H; 
              HH = HH.substring(HH.length-2, HH.length); 
         } 
    } 
	
	var meridian = ""; 
    var result = ""; 
	
    if (fmt.indexOf("HH") == -1) { result += H + ":" + MM; } else { result += HH + ":" + MM; } 
    if (fmt.indexOf("SS") != -1) { result += ":" + SS; } 
    if (fmt.indexOf(" AM") != -1) { result += " " + meridian; } 
	
    return result; 
} 

function checkTimeZone() {
   var rightNow = new Date();
   var date1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
   var date2 = new Date(rightNow.getFullYear(), 6, 1, 0, 0, 0, 0);
   var temp = date1.toGMTString();
   var date3 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
   var temp = date2.toGMTString();
   var date4 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
   var hoursDiffStdTime = (date1 - date3) / (1000 * 60 * 60);
   var hoursDiffDaylightTime = (date2 - date4) / (1000 * 60 * 60);
   /*if (hoursDiffDaylightTime == hoursDiffStdTime) { 
      alert("Time zone is GMT " + hoursDiffStdTime + ".\nDaylight Saving Time is NOT observed here.");
   } else {
      alert("Time zone is GMT " + hoursDiffStdTime + ".\nDaylight Saving Time is observed here.");
   }*/
   return hoursDiffStdTime;
}