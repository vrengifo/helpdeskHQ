        function highlightField (field,select) {
                var i;
                for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                                break;
//                if (NS4) {
//                        field.form.elements[i].bgColor = '<?=FIELD_HIGHLIGHT_ON_COLOR;?>';
//                } else {
                        field.form.elements[i].style.backgroundColor = '<?=FIELD_HIGHLIGHT_ON_COLOR;?>';
//                }
                if (select&&field.form.elements[i].type=="text") field.form.elements[i].select();
        }


        function normalField (field) {
                var i;
                for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                                break;
//                if (NS4) {
//                        field.form.elements[i].bgColor = '<?=FIELD_HIGHLIGHT_OFF_COLOR;?>';
//                } else {
                        field.form.elements[i].style.backgroundColor = '<?=FIELD_HIGHLIGHT_OFF_COLOR;?>';
//                }

        }

        function highlightFieldFirst () {
                var i;
				var oele;
                i = 0;
				if (document.forms.length == 0) return;

					// get a shortcut to the elements array
					oele = document.forms[0].elements;

					// find the first non hidden field
					for (i=0; i < oele.length; i++) {
                        if (oele[i].type!="hidden")  break;
					}
					// if all the fields were hidden return
	  				if (i == oele.length) return;

                    if (oele[i].type=="text") oele[i].select();
                    oele[i].focus();
                    if (oele[i].type=="text"||oele[i].type=="select"||oele[i].type=="textarea") highlightField(oele[i], '<?=FIELD_AUTO_SELECT;?>');
        }