	function setField(name) {
                window.opener.document.mainform.elements[name].value=document.mainform.elements[name].options[document.mainform.elements[name].selectedIndex].value;
//		window.opener.document.mainform.elements[name].value=document.mainform.elements[name].value;
		window.opener.document.mainform.elements[name].focus();
		window.self.close();
	}