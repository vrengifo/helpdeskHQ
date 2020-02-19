function OpenHelp(parameter)
{
	if (parameter)
		parameter = "&" + parameter;
	else
		parameter = "";
	window.open('mod.php?mod=help'+parameter,'win','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1, resizable=1,width=400,height=400');
}
