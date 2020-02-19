// Generic Form Validation
// Jacob Hage (jacob@hage.dk)
var checkObjects	= new Array();
var errors		= "";
var returnVal		= false;
var language		= new Array();
language["header"]	= "Ocurrieron los siguientes errores:"
language["start"]	= "->";
language["field"]	= " Campo ";
language["require"]	= " es requerido";
language["min"]		= " y debe consistir de al menos ";
language["max"]		= " y no debe consistir en más de ";
language["minmax"]	= " y no más de ";
language["chars"]	= " caracteres";
language["num"]		= " y debe contener un número";
language["email"]	= " debe contener un email válido";
language["date"]	= " debe contener una fecha válida";
// -----------------------------------------------------------------------------
// define - Call this function in the beginning of the page. I.e. onLoad.
// n = name of the input field (Required)
// type= string, num, email (Required)
// min = the value must have at least [min] characters (Optional)
// max = the value must have maximum [max] characters (Optional)
// d = (Optional)
// -----------------------------------------------------------------------------
function define(n, type, HTMLname, min, max, d) 
{
  var p;
  var i;
  var x;
  if (!d) d = document;
  if ((p=n.indexOf("?"))>0&&parent.frames.length) 
  {
    d = parent.frames[n.substring(p+1)].document;
	n = n.substring(0,p);
  }
  if (!(x = d[n]) && d.all) x = d.all[n];
  for (i = 0; !x && i < d.forms.length; i++) 
  {
	x = d.forms[i][n];
  }
  for (i = 0; !x && d.layers && i < d.layers.length; i++) 
  {
	x = define(n, type, HTMLname, min, max, d.layers[i].document);
	return x;       
  }
  eval("V_"+n+" = new formResult(x, type, HTMLname, min, max);");
  checkObjects[eval(checkObjects.length)] = eval("V_"+n);
}

function formResult(form, type, HTMLname, min, max) 
{
  this.form = form;
  this.type = type;
  this.HTMLname = HTMLname;
  this.min  = min;
  this.max  = max;
}

function validate() 
{
  if (checkObjects.length > 0) 
  {
	errorObject = "";
	for (i = 0; i < checkObjects.length; i++) 
	{
	  validateObject = new Object();
	  validateObject.form = checkObjects[i].form;
	  validateObject.HTMLname = checkObjects[i].HTMLname;
	  validateObject.val = checkObjects[i].form.value;
	  validateObject.len = checkObjects[i].form.value.length;
	  validateObject.min = checkObjects[i].min;
	  validateObject.max = checkObjects[i].max;
	  validateObject.type = checkObjects[i].type;
	  if (validateObject.type == "num" || validateObject.type == "string") 
	  {
	    if ((validateObject.type == "num" && validateObject.len <= 0) || (validateObject.type == "num" && isNaN(validateObject.val))) 
	    { 
	      errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['num'] + "\n";
	    }
	    else if (validateObject.min && validateObject.max && (validateObject.len < validateObject.min || validateObject.len > validateObject.max)) 
	    { 
	      errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['minmax'] + validateObject.max+language['chars'] + "\n";
	    }
	    else if (validateObject.min && !validateObject.max && (validateObject.len < validateObject.min)) 
	    {
	      errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['chars'] + "\n";
	    }
	    else if (validateObject.max && !validateObject.min &&(validateObject.len > validateObject.max)) 
	    {
	      errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['max'] + validateObject.max + language['chars'] + "\n";
	    }
	    else if (!validateObject.min && !validateObject.max && validateObject.len <= 0) 
	    {
	      errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + "\n";
	    }
	  }
	  else if(validateObject.type == "email")
	  {
		// Checking existense of "@" and ".". 
		// Length of must >= 5 and the "." must 
		// not directly precede or follow the "@"
		if ((validateObject.val.indexOf("@") == -1) || (validateObject.val.charAt(0) == ".") || (validateObject.val.charAt(0) == "@") || (validateObject.len < 6) || (validateObject.val.indexOf(".") == -1) || (validateObject.val.charAt(validateObject.val.indexOf("@")+1) == ".") || (validateObject.val.charAt(validateObject.val.indexOf("@")-1) == ".")) 
		{
		  errors += language['start'] + language['field'] + validateObject.HTMLname + language['email'] + "\n"; 
		}
      }
      else if(validateObject.type == "date")
      {
        //format yyyy-mm-dd
        if ((validateObject.len != 10) || (validateObject.val.charAt(4) != "-") || (validateObject.val.charAt(7) != "-") ) 
		{
		  errors += language['start'] + language['field'] + validateObject.HTMLname + language['date'] + "\n"; 
		}
      }
    }
  }
  if (errors) 
  {
	alert(language["header"].concat("\n" + errors));
	errors = "";
	returnVal = false;
  }
  else
  {
	returnVal = true;
  }
  return (returnVal);
}
