/* BEGIN alertValidateForm

NAME		alertValidateForm(theForm)
SUMMARY wraps validateInput to present a pop-up window on failed input
				for all parameters in the form.
INPUT   'theForm' is the html form which contains the all the inputs to validate
				See validateInput for information outlining the validation field
OUTPUT  Returns true or false; also provides an alert box to the user
				describing what is wrong with the input if there is trouble.
*/


function alertValidateForm(theFormA) {
// pass boolean variable
	var pass=true
// alert string
  var alert_string="Form Validation \n===================\n";
	var diagnostic="";
	var testArray = theFormA.x_required_arguments.value.split(',');
  // BEGIN loop consider elements in form
	for( var i = 0; i < theFormA.length; i++) {
		// BEGIN loop consider elements in the x required arguments array
		for (var counter = 0; counter < testArray.length; counter++){
			// BEGIN validate if element is in the x required argument array
			if (testArray[counter] == theFormA.elements[i].name){
				diagnostic += 
				validateInput(theFormA,theFormA.elements[i],'x');
			}
			// END validate if element is in the x required argument array
		}
		// END loop consider elements in the x required arguments array
	}
	// END loop consider elements in form

	if(diagnostic != '') {
		alert(alert_string+diagnostic);
		return false;
	}

	return true;
// end set alert if needed, return status
}
// END alertValidateForm

/* BEGIN alertValidateInput
NAME		alertValidateInput(theFormB,theInputElement)
SUMMARY wraps validate input to present a pop-up window on failed input
USE     Used for validating a single value in the form, e.g.:
					<input type=text name='element_name'
						onchange="
							wrapValidateInput(document.forms[0], element_name)
					" >
INPUT   'theFormB' is the html form which contains the input to validate
				'theInputElement' is the input element to validate
				See validateInput for information outlining the validation field
OUTPUT  Returns true or false; also provides an alert box to the user
				describing what is wrong with the input if there is trouble.
*/

function alertValidateInput(theFormB,theInputElement) {
	var pass			 = true;
	var diagnostic = validateInput(theFormB,theInputElement);
	
	if (diagnostic != "") {
		pass	= false;
		alert(diagnostic);
	}
	
	return pass
}
// END alertValidateForm

/* BEGIN validateInput
NAME    validateInput(theFormC,theInputElement,disallow_blank)
SUMMARY Validates a given element of a given form against a hidden input
USE     Used by other routines to validate the specific element
        e.g. diagnostic = validateIinput(document.forms[0],S_name)
INPUT   'theFormC' is the html form which contains the input to validate
        'theInputElement' is the input element to validate
					The input element to validate must have corresponding hidden
					input tag in theFormC, and have the structure:
					<input type='hidden' name='arg_check' value='element_name,,
				  display_name,,check_type,,lower_limit,,upper_limit,,
					failure message,,pass_unchanged_date'>
				disallow_blank is a flag that tells the routine to report
				  a diagnostic error if the value is blank.
 
				  Currently suppported check_types include
					1. string
						lower_limit = regexp to match, upper_limit is ignored
					2. number
						lower_limit and upper limit checked against
					3. integer
						lower_limit and upper limit checked against, 
						integer validated
					4. date
						lower_limit and upper limit checked against,
						if the date is unchanged and pass_unchanged_date
            is set to '1', validation is skipped.
OUTPUT		Returns a diagnostic string
					- If the diagnostic string is empty, the validation is ok.
					- If not the diagnostic string contains a messsage,
						the validation is not ok, and the string indicates why.
					
					Pass Modes:
						- If no validation element exists for a given parameter.
						- If the test passes
					Fail Modes
						- If the validation test type does not exist
						- If the test fails
						

*/

function validateInput(theFormC,theInputElement,disallow_blank) {
	var diagnostic='';
	// BEGIN with theFormC
	with(theFormC) {
		// BEGIN loop through validation array
		for ( var j=0; j < theFormC.arg_check.length; j++) {

			// BEGIN skip null entries
			if (arg_check[j] == null) {continue}
			// END skip null entries
			
			// BEGIN split the test array
			// 	testArray[0] is always the element name to check
			var testArray = arg_check[j].value.split(',,');
			// END split test array

			// BEGIN test value on match
			if (testArray[0] == theInputElement.name) {

				// BEGIN empty value test
				if(disallow_blank=='x' && theInputElement.value=='') {
					diagnostic += 'Your input for '+ testArray[1];
					diagnostic += ' cannot be left blank.\n';
				}
				// END empty value test 

				// BEGIN string test
				//  1= display_name, 2 = 'string', 
				//  3 = regular expression test,
				//  4 = 'null', 5 = selection note
				else if (testArray[2] == 'string') {
					var re = new RegExp(testArray[3]);
					RegExp.multiline=1; //fixes problem with \n	
					// BEGIN if test fails, append to diagnostic string
					if(! re.test(theInputElement.value) ) {
						//hack for demonstrations (localhost allowed)
						if (testArray[0]=='S_txEmail'){
							//alert('I am here now');
							var matchpattern='^.*@localhost$'
							var extrare = new RegExp(matchpattern);
							if (extrare.test(theInputElement.value)){
								return '';
							}
						}
						diagnostic += 'Your input for '+ testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += ' has invalid characters: It may ';
						diagnostic += testArray[5] + '\n';
					}
				}
				// END string test

				// BEGIN password test
				//  1= display_name, 2 = 'password', 
				//  3 = regular expression test,
				//  4 = 'null', 5 = selection note
				else if (testArray[2] == 'password') {
					var re = new RegExp(testArray[3]);
					RegExp.multiline=1; //fixes problem with \n	
					// BEGIN if test fails, append to diagnostic string
					if(! re.test(theInputElement.value) ) {
						diagnostic += 'Your input for '+ testArray[1];
						diagnostic += ' has invalid characters: It may ';
						diagnostic += testArray[5] + '\n';
					}
				}
				// END password test


				// BEGIN number test
				//  1 = display name, 2 = 'number', 3 = lower limit,
				//  4 = upper limit, 5 = selection note
				else if (testArray[2] == 'number') {
					// BEGIN validate against general number format
					var re = new RegExp('^-?[0-9]*[.]?[0-9]*$');
					if (! re.test(theInputElement.value) ) {
						diagnostic += 'Your number input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'appears to contain an invalid character.\n';
						diagnostic += '    Only digits and decimal points ';
						diagnostic += 'are allowed.\n';
					}
					// END validate against general number format

					// BEGIN validate lower limit
					else if(testArray[3] != "" && theInputElement.value < Number(testArray[3])) {
						diagnostic += 'Your number input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is below the minimum allowed value of (';
						diagnostic += testArray[3] +').\n';
					} 
					// END validate lower limit

					// BEGIN validate upper limit
					else if (testArray[4] != "" && theInputElement.value > Number(testArray[4])) {
						diagnostic += 'Your number input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is above the maximum allowed value of (';
						diagnostic += testArray[4] +').\n';
					}
					// END validate upper limit
				} 
				// END number test

				// BEGIN money test
				//  1 = display name, 2 = 'money', 3 = lower limit,
				//  4 = upper limit, 5 = selection note
				else if (testArray[2] == 'money') {
					var re = new RegExp('^-?[0-9]*\.?[0-9]?[0-9]?$');
					// BEGIN validate against general money format
					if (! re.test(theInputElement.value) ) {
						diagnostic += 'Your monetary input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'appears to contain invalid characters.\n  ';
						diagnostic += '    Only numbers with a maximum of 2 decimal ';
						diagnostic += 'places are allowed.\n';
					}
					// END validate against general money format

					// BEGIN validate lower limit
					else if(testArray[3] != "" && theInputElement.value < Number(testArray[3])) {
						diagnostic += 'Your monetary input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is below the minimum allowed value of (';
						diagnostic += testArray[3] +').\n';
					}
					// END validate lower limit

					// BEGIN validate upper limit
					else if (testArray[4] != "" && theInputElement.value > Number(testArray[4])) {
						diagnostic += 'Your monetary input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is above the maximum allowed value of (';
						diagnostic += testArray[4] +').\n';
					}
					// END validate upper limit
				}
				// END money test

				// BEGIN integer test
				//  1 = display name, 2 = 'integer', 3 = lower limit,
				//  4 = upper limit, 5 = selection note
				else if (testArray[2] == 'integer') {

					// BEGIN validate against general integer format
					var re = new RegExp('^-?[0-9]*$');
					if (! re.test(theInputElement.value) ) {
						diagnostic += 'Your integer input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'appears to contain invalid characters.\n';
						diagnostic += '    Only digits are allowed.\n';
					}
					// END validate against general integer format

					// BEGIN validate lower limit
					else if(testArray[3] != "" && theInputElement.value < Number(testArray[3])) {
						diagnostic += 'Your integer input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is below the minimum allowed value of (';
						diagnostic += testArray[3] +').\n';
					} 
					// END validate lower limit

					// BEGIN validate upper limit
					else if (testArray[4] != "" && theInputElement.value > Number(testArray[4])) {
						diagnostic += 'Your integer input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is above the maximum allowed value of (';
						diagnostic += testArray[4] +').\n';
					}
					// END validate upper limit
				}
				// END integer test

				// BEGIN date test
				//  1 = display name, 2 = 'date', 3 = lower limit,
				//  4 = upper limit, 5 = selection note, 
				//  6 = valid if not changed
				else if (testArray[2] == 'date') {


					// BEGIN if valid if not changed flag is set, return true
					if (testArray[6]=='1') {
						// NOTE: The following is broken.  I don't know why. -- msm
						// the defaultValue attribute appears not to work in NS4.7
						//var msg = "Element Name          : " + theInputElement.name;
						//msg +=  "\nElement value         : " + theInputElement.value;
						//msg +=  "\nElement default value : " + theInputElement.defaultValue;
						//alert(msg);
						if (theInputElement.value == theInputElement.defaultValue){
							return '';
						}
					}
					// END if valid if not changed flag is set, return true

					// BEGIN validate against general date format
					var re = new RegExp('^[0-9]*$');
					if (! re.test(theInputElement.value) ) {
						diagnostic += 'Your date input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'appears to contain invalid characters.  ';
						diagnostic += '    Only digits are allowed.\n';
					}
					// END validate against general date format

					// BEGIN validate lower limit
					else if(testArray[3] != "" && theInputElement.value < Number(testArray[3])) {
						diagnostic += 'Your date input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is below the minimum allowed date of (';
						diagnostic += testArray[3] +').\n';
					} 
					// END validate lower limit

					// BEGIN validate upper limit
					else if (testArray[4] != "" && theInputElement.value > Number(testArray[4])) {
						diagnostic += 'Your date input for ' + testArray[1];
						diagnostic += ' (' + theInputElement.value + ') ';
						diagnostic += 'is above the maximum allowed date of (';
						diagnostic += testArray[4] +').\n';
					}
					// END validate upper limit
				} 
				// END date test

				// BEGIN general diagnostic error for unknown type
				else {
					diagnostic += 'Requested validation type ' + testArray[1];
					diagnostic += ' is not supported.  See the UIS administrator\n';
				} 
				// END general diagnostic error for unknown type
				return diagnostic;
			} 
			// END test value on match
		} 
		// END loop through validation array
	} 
	// END with theFormC

	// BEGIN return default pass behaviour
	return '';
	// END return default pass behaviour
}
// END validateInput


