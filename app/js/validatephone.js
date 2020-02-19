	function validatephone(field) {
		var valid = "0123456789()-+ x"
		var temp;
		for (var i=0; i<field.value.length; i++) {
			temp = "" + field.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				field.value=(field.value.substring(0,i)+(field.value.substring(i+1,field.value.length)));
				i--
			}
		}
	}