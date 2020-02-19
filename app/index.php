<?php
  include_once("includes/my_defines.php");
?>
<html>
<head><title><?=$pTitle?></title>
<STYLE>
td { font-family: Tahoma, Verdana, Arial, sans-serif; }
body { font-family: Tahoma, Verdana, Arial, sans-serif; }
</STYLE>
</head>
<body link=#0000ff vlink=#0000ff bgcolor=#ffffff background="images/bgBasic.gif" topmargin=0 marginheight=0>
<table width=100% border=0 cellspacing=1 cellpadding=0 bgcolor=#224466>
 <tr height=1 bgcolor=#94ACC8>
  <td BGCOLOR="#FFFFFF" WIDTH="120" height="66" align="center" valign="middle"><img src="images/logo.gif" border=0></td>
  <td align=center valign=middle nowrap>HELPDESK HQ1</td>
 </tr>
</table>
<!--
<SCRIPT LANGUAGE="JavaScript">function helpWindow() { window.open('s360.exe?page=LoginHelp','Help','width=680,height=500,resizable=1,scrollbars=1'); }</SCRIPT>
-->
<table width=100% border=0 cellspacing=0 cellpadding=0>
 <tr>
  <td width="99%" background="images/mb_top.gif" height=7><img src="images/spacer.gif" height=7></td>
  <td width=7 bgcolor="#224466"><img src="images/mb_topright.gif"></td>
  <td width="1%" bgcolor="#224466"><img src="images/spacer.gif"></td>
 </tr>
 <tr>
  <td bgcolor="#dddddd" align="center" width="70%">
<p><font size=+1>
<?php 
	extract($_REQUEST);
	if (!empty($error)){
		echo "Error, chequee su usuario y clave";
	}
?>
</font></p>

<form method="post" action="login.php">
  <table BORDER=0>
    <tr> 
      <td nowrap>Usuario</td>
      <td><input type=text name=username></td>
    </tr>
    <tr>
      <td nowrap>Contrase&ntilde;a</td>
      <td><input type=password name=password></td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type=submit name=Submit value="Entrar">
      </td>
    </tr>
  </table>
</form>

      <p ALIGN=left></p>
 </td>
  <td background="images/mb_right2.gif" width=7 height=14 valign=top bgcolor="#224466"><img src="images/mb_right1.gif"></td>
  <td width="1%" valign=top><table width=100% border=0 cellspacing=0 cellpadding=0 bgcolor="#224466"><tr><td height=14><img src="images/spacer.gif" height=14></td></tr></table></td>
 </tr>
 <tr>
  <td background="images/mb_bottom.gif" height=7><img src="images/spacer.gif" height=7></td>
  <td><img src="images/mb_bottomright.gif"></td>
  <td><img src="images/spacer.gif"></td>
 </tr>
</table>
<br>
<p STYLE="font-size:10px;">
Version: 1.0 
</P>
<p><font size="-1">Hospital de la Polic&iacute;a Nacional</font></p>
</body>
</html>
