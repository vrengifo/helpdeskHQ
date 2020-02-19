var clockID = 0;

function UpdateClock() {
   if(clockID) {
      clearTimeout(clockID);
      clockID  = 0;
   }

   var tDate = new Date();
   var hours = tDate.getHours();
   var minutes = tDate.getMinutes();
   var month = tDate.getMonth()+1;
   var amPm = "am";

   if (hours > 12)
   {
	   hours = hours - 12
	   amPm = "pm"
   }
   else if (hours < 10)
   {
	   hours = "0" + hours;
   }

   if (minutes < 10)
   {
	   minutes = "0" + minutes;
   }


   document.datetime.time.value = "" 
                                 + hours + ":" 
                                 + minutes + " " 
								 + amPm;

   document.datetime.date.value = "" 
								 + month + "/"
                                 + tDate.getDate() + "/" 
                                 + tDate.getFullYear();
   
   clockID = setTimeout("UpdateClock()", 1000);
}
function StartClock() {
   clockID = setTimeout("UpdateClock()", 500);
}

function StopClock() {
   if(clockID) {
      clearTimeout(clockID);
      clockID  = 0;
   }
}

