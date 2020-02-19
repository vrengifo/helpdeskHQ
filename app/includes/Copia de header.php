<html><head>
<?
        if (strrpos($PHP_SELF,"/")) { //get page name
                $docfilename=substr($PHP_SELF,strrpos($PHP_SELF,"/")+1,strlen($PHP_SELF)-strrpos($PHP_SELF,"/"));
        } else {
                $docfilename=substr($PHP_SELF,1);
        };
        if ($printable&&!$nonprintable) { 
                echo '<link rel="stylesheet" type="text/css" href="includes/style/print.css">';
                if (PRINT_AUTO_POPUP) {
                        echo '<script language="JavaScript">'."\n";
                        echo '        function printMe() {'."\n";
                        echo '                self.print();'."\n";
                        echo '        }'."\n";
                        echo '</script>';
                        $printmestr=";printMe()";
                };
        } else {
                if (file_exists('includes/style/bluish.css')) {
                        if (strtolower(substr($docfilename,0,4))=="help") {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/help'.$user_stylesheet.'.css">';
                        } else {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/'.$user_stylesheet.'.css">';
                        };
                } else {
                        if (strtolower(substr($docfilename,0,4))=="help") {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/help.css">';
                        } else {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/bluish.css">';
                        };
                };
        };
?>

<? if (SHOW_TOOLTIPS): ?>
<script type="text/javascript" language="JavaScript"><!--
function toggle(object) {
  var Event = window.event || arguments.callee.caller.arguments[0];

  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else {
      document.getElementById(object).style.left = Event.x+15;
      document.getElementById(object).style.top  = Event.y-20;
      document.getElementById(object).style.visibility = 'visible';
      }
  }

  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' ||
     document.layers[object].visibility == 'show' )
      document.layers[object].visibility = 'hidden';
    else {
      document.layers[object].left = Event.x+15;
      document.layers[object].top  = Event.y-20;
      document.layers[object].visibility = 'visible';
      }
  }

  else if (document.all) {
    if (document.all[object].style.visibility == 'visible')
      document.all[object].style.visibility = 'hidden';
    else {
      document.all[object].style.pixelLeft = document.body.scrollLeft + Event.x + 1;
      document.all[object].style.pixelTop = document.body.scrollTop + Event.y + 1;
      document.all[object].style.visibility = 'visible';
      }
  }

  return false;
}
//--></script>
<? else: ?>
        <script language="JavaScript">
        function toggle(object) {
        }
        </script>
<? endif; ?>
        <script language="JavaScript" src="js/overlib.js"></script>
        <script language="JavaScript" src="js/donothing.js"></script>
        <script language="JavaScript" src="js/confirm.js"></script>
        
		<script language="JavaScript" src="js/date-picker.js"></script>
		<script language="JavaScript" src="js/valida_ci.js"></script>
        <script language="JavaScript">
        <!--
<?         if (FIELD_TAB): ?>
                <? require_once('js/handleenter.js'); ?>
        <? else: ?>
                function handleEnter (field, event) {
                }
        <? endif; ?>
        //-->
        </script>

        <script language="JavaScript">
        <!--
        <? if (FIELD_HIGHLIGHT): ?>
                <? require_once('js/highlightfield.js'); ?>
        <? else: ?>
                function highlightField (field,select) {
                }

                function normalField (field) {
                }

                function highlightFieldFirst () {
                }
        <? endif; ?>
        //-->
        </script>
        <script language="Javascript1.1">
            function imgchange(imgName,imgSrc) {
                if (document.images) {
                    document.images[imgName].src = imgSrc;
                }
            }
            function imgchange2(imgName, imgSrc) {
                if (document.images) {
                    document[imgName].src = eval(imgSrc + ".src");
                }
            }
        </script>
		
<SCRIPT LANGUAGE="Javascript">
function cambiar_action(forma,url_destino)
{	
    forma.action=url_destino;	
}

function chequear_cedula()
{

}
function valida()
{

}

function busca_combo(group,valor)
{
  //alert(valor);
  var tarreglo=group.length;
  var aux=0;
  var flag=0;//bandera
  
  var tvalor=valor.length;
  
  var i;
  var vindice;
  var vtexto;
  var vvalor;
  var cad;
  
  //alert(tvalor);
  if (tvalor>0)//buscar 1ra ocurrencia
  {
    i=0;
  	while((i<tarreglo)&&(!flag))
  	//for (i=0;i<group.length;i++)  
    {
  	  vtexto=group[i].text;
  	  vvalor=group[i].value;
  	  vindice=i;
  	  cad="vtexto:" + vtexto + " vvalor:" + vvalor + " vindice:" + vindice;
  	  //alert(cad);
  	  
  	  
  	  if(vtexto.substr(0,tvalor)==valor)
  	  {
  	  	flag=1;
  	  }  
  	    i=i+1;
    }
  }
  if(flag==1)
    group.options[vindice].selected=true;
  //else
    //group.options[0].selected=true;
  //alert(vindice);    
}

//busca el separador decimal cargado en el browser
function jsSeparador()
{
  var resultado;
  var res;
  resultado=eval(1/2);
  res=new String(resultado);
  res=res.substring(1,2);    
  return(res);
}

//sustituye en una cadena cad, "busca" por "por" 
function jsSustituir(cad,busca,por)
{
  var i;  
  var aux,recu;
  
  if(busca==por)
  {
  	return(cad);
  }
  else
  {
    aux=""; 
    for(i=0;i<cad.length;i++)
    {
	  if(cad.charAt(i)==busca)
	  {
	    recu=por;
	  }  
	  else
	  {
	    recu=cad.charAt(i);  
	  }
	  aux=aux.concat(recu); 
    }
    return(aux);
  }
}

</script>		
		
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/validation.js"></script>
<SCRIPT LANGUAGE="JavaScript">var formissent = 0;function onlyOneSubmit() {if (!formissent) {formissent = 1; return true; } else { return false; }}</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function hideRow(rowid) { document.getElementById(rowid).style.display = "none"; }
var iFrameIndex = new Number(0);
/*
function setNotification()
{
var iFrameId = new String("notify");
var args = setNotification.arguments;
if (args.length < 2) return false;
iFrameId += iFrameIndex;
iFrameIndex < 7 ? iFrameIndex++ : iFrameIndex=0;
iFrameURL = new String("s360.exe?");
for (i=0; i<args.length; i+=2)
{
	iFrameURL += args[i] + "=" + args[i+1] + "&";
}
//alert(iFrameURL);
//alert(iFrameId);
eval("var cf = window.frames."+iFrameId);
cf.location.replace(iFrameURL);
}
*/
</SCRIPT>
<!--
pv
-->
<SCRIPT LANGUAGE="JavaScript">
function fOpenWindow(vurl,vtitle,vwidth,vheight) 
{
 var cad;
 cad='width=' + vwidth + ',height=' + vheight + ',resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0';
 //alert (vurl);
 //alert (vtitle);
 //alert (cad);
 window.open(vurl,vtitle,cad);
}
</SCRIPT>
<!--
pv
-->
<SCRIPT LANGUAGE="Javascript">
function over( style ) { style.borderLeftColor="#FFFFFF"; style.borderTopColor="#FFFFFF"; style.borderRightColor="#333333"; style.borderBottomColor="#333333"; }
function mout( style ) { style.borderColor="#90A8C8" }
function tout( style ) { style.borderColor="#94ACC8" }
function overborder( style, clr ) {style.borderColor=clr }
function moutborder( style, clr ) {style.borderColor=clr }
function showstatus( lbl ) { status = lbl; return true; }
function hidestatus() {status = ""; }
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function showFullContents(dobj)
{
var dwidth = parseInt(dobj.style.width) * 1.5;
var dheight = parseInt(dobj.style.height) * 1.5;
dobj.style.zIndex = 90;
if (dheight < 200) dobj.style.height = dheight;
if (dwidth < 200) dobj.style.width = dwidth;
dobj.style.clip = "rect(auto,"+dwidth+"px,"+dheight+"px,auto)";
}
function hideFullContents(dobj,dheight,dwidth,dzindex)
{
dobj.style.zIndex = dzindex;
dobj.style.height = dheight;
dobj.style.width = dwidth;
dobj.style.clip = "rect(auto,"+dwidth+"px,"+dheight+"px,auto)";
}

</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
var chk = 1
function checkAll(fmnm,cbnm)
{
var cb = document.forms[fmnm].elements[cbnm];
	if (cb && cb.value) 
	{
		cb.checked = chk ? 1 : 0;
		highlightRow(cb.value,cb.checked);
	}
	else if (cb)
	{
		for (i=0;i<cb.length;i++)
		{
			cb[i].checked = chk ? 1 : 0;
			highlightRow(cb[i].value,cb[i].checked);
		}
	}
	chk = (chk) ? 0 : 1;
}
function highlightRow(rid,chk)
{
var tr = document.getElementById(eval("'tr"+rid+"'"));
	if (chk && tr)
	{
		tr.style.backgroundColor = "#90A8C8";
	}
	else if (tr)
	{
		tr.style.backgroundColor = "";
	
	}
}

</SCRIPT>
<TITLE><?=$pTitle?></TITLE>
</head>
<?php
	if (isset($principal))
	{
		$valida="valida();";
	}
	else
	{
		$valida="";
	}
?>
<body onLoad="<?=$valida?>highlightFieldFirst()<?=$printmestr.$menubdstr;?>" class="<?= $bodystyle ?>" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table border=0 width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign=top> 
<!-- startprint -->