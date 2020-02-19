// ===================================================================
// Author: Matt Kruse <mkruse@netexpress.net>
// WWW: http://www.mattkruse.com/
//
// NOTICE: You may use this code for any purpose, commercial or
// private, without any further permission from the author. You may
// remove this notice from your final code if you wish, however it is
// appreciated by the author if at least my web site address is kept.
//
// You may *NOT* re-distribute this code in any way except through its
// use. That means, you can include it in your product, or your web
// site, or any other form where the code is actually being used. You
// may not put the plain javascript up on your site for download or
// include it in your javascript libraries for download. Instead,
// please just point to my URL to ensure the most up-to-date versions
// of the files. Thanks.
// ===================================================================

var MONTH_NAMES = new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

// ------------------------------------------------------------------
// isDate ( date_string, format_string )
//
// Returns true if date string matches format of format string and
// is a valid date. Else returns false.
//
// It is recommended that you trim whitespace around the value before
// passing it to this function, as whitespace is NOT ignored!
// ------------------------------------------------------------------
function isDate(val,format) {
	var date = getDateFromFormat(val,format);
	if (date == 0) { return false; }
	return true;
	}

// -------------------------------------------------------------------
// compareDates(date1,date1format,date2,date2format)
//   Compare two date strings to see which is greater.
//   Returns:
//   1 if date1 is greater than date2
//   0 if date2 is greater than date1 of if they are the same
//  -1 if either of the dates is in an invalid format
// -------------------------------------------------------------------
function compareDates(date1,dateformat1,date2,dateformat2) {
	var d1 = getDateFromFormat(date1,dateformat1);
	var d2 = getDateFromFormat(date2,dateformat2);
	if (d1==0 || d2==0) {
		return -1;
		}
	else if (d1 > d2) {
		return 1;
		}
	return 0;
	}

// ------------------------------------------------------------------
// formatDate (date_object, format)
//
// Returns a date in the output format specified.
// The format string uses the same abbreviations as in getDateFromFormat()
// ------------------------------------------------------------------
function formatDate(date) {
	if (date.value.substring(0,4)==Number(date.value.substring(0,4))&&date.value.substring(5,7)==Number(date.value.substring(5,7))&&date.value.substring(8,10)==Number(date.value.substring(8,10))) { //test for correct format
		date.value=date.value.substring(0,4)+"-"+date.value.substring(5,7)+"-"+date.value.substring(8,10);
	} else {
	datev=new Date(date.value);
	format = "yyyy-MM-dd";
	var result = "";
	var i_format = 0;
	var c = "";
	var token = "";
	var y = datev.getYear()+"";
	var M = datev.getMonth()+1;
	var d = datev.getDate();
	var H = datev.getHours();
	var m = datev.getMinutes();
	var s = datev.getSeconds();
	var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
	// Convert real date parts into formatted versions
	// Year
	if (y.length < 4) {
		if (y < 60) {
			y = y-0+100;
		}
		y = y-0+1900;
		}
	y = ""+y;
	yyyy = y;
	yy = y.substring(2,4);
	// Month
	if (M < 10) { MM = "0"+M; }
		else { MM = M; }
	MMM = MONTH_NAMES[M-1];
	// Date
	if (d < 10) { dd = "0"+d; }
		else { dd = d; }
	// Hour
	h=H+1;
	K=H;
	k=H+1;
	if (h > 12) { h-=12; }
	if (h == 0) { h=12; }
	if (h < 10) { hh = "0"+h; }
		else { hh = h; }
	if (H < 10) { HH = "0"+K; }
		else { HH = H; }
	if (K > 11) { K-=12; }
	if (K < 10) { KK = "0"+K; }
		else { KK = K; }
	if (k < 10) { kk = "0"+k; }
		else { kk = k; }
	// AM/PM
	if (H > 11) { ampm="PM"; }
	else { ampm="AM"; }
	// Minute
	if (m < 10) { mm = "0"+m; }
		else { mm = m; }
	// Second
	if (s < 10) { ss = "0"+s; }
		else { ss = s; }
	// Now put them all into an object!
	var value = new Object();
	value["yyyy"] = yyyy;
	value["yy"] = yy;
	value["y"] = y;
	value["MMM"] = MMM;
	value["MM"] = MM;
	value["M"] = M;
	value["dd"] = dd;
	value["d"] = d;
	value["hh"] = hh;
	value["h"] = h;
	value["HH"] = HH;
	value["H"] = H;
	value["KK"] = KK;
	value["K"] = K;
	value["kk"] = kk;
	value["k"] = k;
	value["mm"] = mm;
	value["m"] = m;
	value["ss"] = ss;
	value["s"] = s;
	value["a"] = ampm;
	while (i_format < format.length) {
		// Get next token from format string
		c = format.charAt(i_format);
		token = "";
		while ((format.charAt(i_format) == c) && (i_format < format.length)) {
			token += format.charAt(i_format);
			i_format++;
			}
		if (value[token] != null) {
			result = result + value[token];
			}
		else {
			result = result + token;
			}
		}
	date.value=result;
	}
	}
	
// ------------------------------------------------------------------
// Utility functions for parsing in getDateFromFormat()
// ------------------------------------------------------------------
function _isInteger(val) {
	var digits = "1234567890";
	for (var i=0; i < val.length; i++) {
		if (digits.indexOf(val.charAt(i)) == -1) { return false; }
		}
	return true;
	}
function _getInt(str,i,minlength,maxlength) {
	for (x=maxlength; x>=minlength; x--) {
		var token = str.substring(i,i+x);
		if (token.length < minlength) {
			return null;
			}
		if (_isInteger(token)) { 
			return token;
			}
		}
	return null;
	}
// ------------------------------------------------------------------
// END Utility Functions
// ------------------------------------------------------------------
	
// ------------------------------------------------------------------
// getDateFromFormat( date_string , format_string )
//
// This function takes a date string and a format string. It matches
// If the date string matches the format string, it returns the 
// getTime() of the date. If it does not match, it returns 0.
// 
// This function uses the same format strings as the 
// java.text.SimpleDateFormat class, with minor exceptions.
// 
// The format string consists of the following abbreviations:
// 
// Field        | Full Form          | Short Form
// -------------+--------------------+-----------------------
// Year         | yyyy (4 digits)    | yy (2 digits), y (2 or 4 digits)
// Month        | MMM (name or abbr.)| MM (2 digits), M (1 or 2 digits)
// Day of Month | dd (2 digits)      | d (1 or 2 digits)
// Hour (1-12)  | hh (2 digits)      | h (1 or 2 digits)
// Hour (0-23)  | HH (2 digits)      | H (1 or 2 digits)
// Hour (0-11)  | KK (2 digits)      | K (1 or 2 digits)
// Hour (1-24)  | kk (2 digits)      | k (1 or 2 digits)
// Minute       | mm (2 digits)      | m (1 or 2 digits)
// Second       | ss (2 digits)      | s (1 or 2 digits)
// AM/PM        | a                  |
//
// Examples:
//  "MMM d, y" matches: January 01, 2000
//                      Dec 1, 1900
//                      Nov 20, 00
//  "m/d/yy"   matches: 01/20/00
//                      9/2/00
//  "MMM dd, yyyy hh:mm:ssa" matches: "January 01, 2000 12:30:45AM"
// ------------------------------------------------------------------
function getDateFromFormat(val,format) {
	val = val+"";
	format = format+"";
	var i_val = 0;
	var i_format = 0;
	var c = "";
	var token = "";
	var token2= "";
	var x,y;
	var now   = new Date();
	var year  = now.getYear();
	var month = now.getMonth()+1;
	var date  = now.getDate();
	var hh    = now.getHours();
	var mm    = now.getMinutes();
	var ss    = now.getSeconds();
	var ampm  = "";
	
	while (i_format < format.length) {
		// Get next token from format string
		c = format.charAt(i_format);
		token = "";
		while ((format.charAt(i_format) == c) && (i_format < format.length)) {
			token += format.charAt(i_format);
			i_format++;
			}
		// Extract contents of value based on format token
		if (token=="yyyy" || token=="yy" || token=="y") {
			if (token=="yyyy") { x=4;y=4; }// 4-digit year
			if (token=="yy")   { x=2;y=2; }// 2-digit year
			if (token=="y")    { x=2;y=4; }// 2-or-4-digit year
			year = _getInt(val,i_val,x,y);
			if (year == null) { return 0; }
			i_val += year.length;
			if (year.length == 2) {
				if (year > 70) {
					year = 1900+(year-0);
					}
				else {
					year = 2000+(year-0);
					}
				}
			}
		else if (token=="MMM"){// Month name
			month = 0;
			for (var i=0; i<MONTH_NAMES.length; i++) {
				var month_name = MONTH_NAMES[i];
				if (val.substring(i_val,i_val+month_name.length).toLowerCase() == month_name.toLowerCase()) {
					month = i+1;
					if (month>12) { month -= 12; }
					i_val += month_name.length;
					break;
					}
				}
			if (month == 0) { return 0; }
			if ((month < 1) || (month>12)) { return 0; }
			// TODO: Process Month Name
			}
		else if (token=="MM" || token=="M") {
			x=token.length; y=2;
			month = _getInt(val,i_val,x,y);
			if (month == null) { return 0; }
			if ((month < 1) || (month > 12)) { return 0; }
			i_val += month.length;
			}
		else if (token=="dd" || token=="d") {
			x=token.length; y=2;
			date = _getInt(val,i_val,x,y);
			if (date == null) { return 0; }
			if ((date < 1) || (date>31)) { return 0; }
			i_val += date.length;
			}
		else if (token=="hh" || token=="h") {
			x=token.length; y=2;
			hh = _getInt(val,i_val,x,y);
			if (hh == null) { return 0; }
			if ((hh < 1) || (hh > 12)) { return 0; }
			i_val += hh.length;
			hh--;
			}
		else if (token=="HH" || token=="H") {
			x=token.length; y=2;
			hh = _getInt(val,i_val,x,y);
			if (hh == null) { return 0; }
			if ((hh < 0) || (hh > 23)) { return 0; }
			i_val += hh.length;
			}
		else if (token=="KK" || token=="K") {
			x=token.length; y=2;
			hh = _getInt(val,i_val,x,y);
			if (hh == null) { return 0; }
			if ((hh < 0) || (hh > 11)) { return 0; }
			i_val += hh.length;
			}
		else if (token=="kk" || token=="k") {
			x=token.length; y=2;
			hh = _getInt(val,i_val,x,y);
			if (hh == null) { return 0; }
			if ((hh < 1) || (hh > 24)) { return 0; }
			i_val += hh.length;
			h--;
			}
		else if (token=="mm" || token=="m") {
			x=token.length; y=2;
			mm = _getInt(val,i_val,x,y);
			if (mm == null) { return 0; }
			if ((mm < 0) || (mm > 59)) { return 0; }
			i_val += mm.length;
			}
		else if (token=="ss" || token=="s") {
			x=token.length; y=2;
			ss = _getInt(val,i_val,x,y);
			if (ss == null) { return 0; }
			if ((ss < 0) || (ss > 59)) { return 0; }
			i_val += ss.length;
			}
		else if (token=="a") {
			if (val.substring(i_val,i_val+2).toLowerCase() == "am") {
				ampm = "AM";
				}
			else if (val.substring(i_val,i_val+2).toLowerCase() == "pm") {
				ampm = "PM";
				}
			else {
				return 0;
				}
			}
		else {
			if (val.substring(i_val,i_val+token.length) != token) {
				return 0;
				}
			else {
				i_val += token.length;
				}
			}
		}
	// If there are any trailing characters left in the value, it doesn't match
	if (i_val != val.length) {
		return 0;
		}
	// Is date valid for month?
	if (month == 2) {
		// Check for leap year
		if ( ( (year%4 == 0)&&(year%100 != 0) ) || (year%400 == 0) ) { // leap year
			if (date > 29){ return false; }
			}
		else {
			if (date > 28) { return false; }
			}
		}
	if ((month==4)||(month==6)||(month==9)||(month==11)) {
		if (date > 30) { return false; }
		}
	// Correct hours value
	if (hh<12 && ampm=="PM") {
		hh+=12;
		}
	else if (hh>11 && ampm=="AM") {
		hh-=12;
		}
	var newdate = new Date(year,month-1,date,hh,mm,ss);
	return newdate.getTime();
	}

