<?php
/**
 * Administrar la tabla usuario
 *
 */
include_once("class/c_interfaz.php");
include_once("adodb/tohtml.inc.php");
include_once("class/c_perfil.php");
include_once("class/c_tipousuario.php");
include_once("class/c_area.php");

class c_usuario implements c_interfaz 
{
  //atributos base
  var $usu_id;
  var $usu_clave;
  
  var $per_id;
  /**
   * campo usu_nombre
   *
   * @var cadena
   */
  var $usu_nombre;
  
  var $are_id;
  var $tipusu_id;
  var $usu_mail;
  
  /**
   * Variable de conexión a Base de Datos
   *
   * @var unknown_type
   */
  var $con;
  
  var $msg;
  
  /**
   * Constructor
   *
   * @param conexionBD $conDB
   */
  function __construct(&$conDB)
  {
  	$this->con=$conDB;
  	$this->usu_id="";
  	$this->usu_clave="";
  	$this->per_id=0;
  	$this->usu_nombre="";
  	
  	$this->are_id="";
  	$this->tipusu_id="";
  	$this->usu_mail="";
  	
  	$this->msg="";
  }
  
  function autenticar($usuario,$clave)
  {
    $sql=<<<cad
        select USU_ID 
        from USUARIO
        where
        USU_ID='$usuario' and USU_CLAVE='$clave'   
cad;
    $rs=&$this->con->Execute($sql);
    if($rs->EOF)
    {
      $res="";  
    }
    else 
    {
      $res=$rs->fields[0];
    }
    return($res);
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select USU_ID from USUARIO
	    where USU_ID='$this->usu_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
	}
	else 
	{
	  $res=$rs->fields[0];	
	}
	return($res);
  }
  
  function info($id)
  { 
    $sql=<<<vic
	    select USU_ID,PER_ID,USU_NOMBRE,USU_CLAVE,
	    are_id,tipusu_id,usu_mail  
	    from USUARIO
	    where USU_ID='$id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->usu_id="0";
	  $this->per_id="";
	  $this->usu_nombre="";
	  $this->usu_clave="";
	  $this->are_id="";
	  $this->tipusu_id="";
	  $this->usu_mail="";
	}
	else 
	{
	  $this->usu_id=$rs->fields[0];
	  $this->per_id=$rs->fields[1];
	  $this->usu_nombre=$rs->fields[2];
	  $this->usu_clave=$rs->fields[3];
	  $this->are_id=$rs->fields[4];
	  $this->tipusu_id=$rs->fields[5];
	  $this->usu_mail=$rs->fields[6];
	  $res=$id;
	}
	return($res);	
  }
  //no programadas
  function add()
  {
  	$existe=$this->exist();
  	if(strlen($existe)==0)
  	{
  	  $sql=<<<va
  	insert into USUARIO
  	(USU_ID,PER_ID,USU_NOMBRE,USU_CLAVE,
  	are_id,tipusu_id,usu_mail)
  	values
  	('$this->usu_id',$this->per_id,'$this->usu_nombre','$this->usu_clave',
  	'$this->are_id','$this->tipusu_id','$this->usu_mail')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
      }
      else 
      {
        $res=0;
        $this->msg="Error de ingreso a base de datos";
      }
  	}
  	else
  	{
  	  $res=$existe; 	
  	  $this->msg="Dato ya existe";
	}
  	return($res);
  }
  
  function update($id)
  {
  	$oAux=new c_usuario($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update USUARIO set
        PER_ID='$this->per_id', 
        are_id='$this->are_id', 
        tipusu_id='$this->tipusu_id',
        usu_mail='$this->usu_mail' 
        where 
        USU_ID='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$sql=<<<sql
  	delete from usuario 
  	where USU_ID='$id'
sql;
    $rs=&$this->con->Execute($sql);
  	
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by USU_ID ";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select USU_ID,USU_NOMBRE
		from USUARIO
		$cadOrderby
cad;
	return($sql);
  }
  
  function sqlSelectNombre($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by USU_ID ";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select USU_ID,USU_NOMBRE
		from USUARIO
		$cadOrderby
cad;
	return($sql);
  }
  /**
   * Enter description here...
   *
   * @param unknown_type $nombreCampo
   * @param unknown_type $valorCampo
   * @param cadena $estilo
   */
  function htmlSelect($nombreCampo,$valorCampo,$estilo)
  {
  	$cadEstilo=strlen($estilo)>0?$estilo:"";
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by usu_id,per_id"));
  	$cadOpcion="";
  	while (!$rs->EOF) 
  	{
  	  $valor=$rs->fields[0];
  	  $texto=$rs->fields[1];
  		
  	  $cadSelected="";
  	  if($valor==$valorCampo)
  	    $cadSelected=" selected";
  	  $datoOpcion='<option value="'.$valor.'" '.$cadSelected.' >'.$texto.'</option>';	
  	  $cadOpcion.=$datoOpcion;
  	  
  	  $rs->MoveNext();
  	}
  	
  	$cad=<<<cad
  	<select name="$nombreCampo" $cadEstilo >
  	  <option value="">Selecciona Dato</option>
  	  $cadOpcion
  	</select>
cad;
    return($cad);
  }
  
  function listall($orderby)
  {
  }

//html
  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return int
   */
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
        $this->usu_id=$dato[0];
	    $this->usu_clave=$dato[1];
	    $this->usu_nombre=$dato[2];
	    $this->per_id=$dato[3];
	    $this->are_id=$dato[4];
	    $this->tipusu_id=$dato[5];
	    $this->usu_mail=$dato[6];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
	    $this->usu_id=$dato[0];
	    $this->usu_clave=$dato[1];
	    $this->usu_nombre=$dato[2];
	    $this->per_id=$dato[3];
	    $this->are_id=$dato[4];
	    $this->tipusu_id=$dato[5];
	    $this->usu_mail=$dato[6];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }

  /**
   * Crea el interfaz de Administración
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $destAdd
   * @param string $destUpdate
   * @param string $titulo
   * @return string
   */
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["bUsuId"])>0)
  	  $aSql=" AND U.USU_ID LIKE '".$post["bUsuId"]."%' ";
  	  
  	if(strlen($post["bNombre"])>0)
  	  $aSql.=" AND U.USU_NOMBRE LIKE '".$post["bNombre"]."%' ";  
  	  
  	if(strlen($post["bPerfil"])>0)
  	  $aSql.=" AND U.PER_ID ='".$post["bPerfil"]."' ";  
  	
  	$sql=<<<va
  	  select U.USU_ID AS UNO,
	  P.PER_NOMBRE,U.USU_ID AS DOS,U.USU_NOMBRE,
	  a.are_nombre, U.usu_mail, 
	  U.USU_ID AS TRES 
	  from USUARIO U, PERFIL P, area a 
	  where P.PER_ID=U.PER_ID 
	  and a.are_id=u.are_id 
	  $aSql 
	  order by U.USU_ID 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","Perfil","UserID","Nombre","Area","E-mail","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
  /**
   * Crea el interfaz para añadir
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @return string
   */
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
	$oPerfil=new c_perfil($this->con);
	$oArea=new c_area($this->con);
	$oTipoUsuario=new c_tipousuario($this->con);
	
    $campo=array(
				array("etiqueta"=>"* Usuario Id","nombre"=>"tUsername","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Clave","nombre"=>"tClave","tipo_campo"=>"password","sql"=>"","valor"=>""),
				array("etiqueta"=>"*  Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Perfil","nombre"=>"tPerfil","tipo_campo"=>"select","sql"=>$oPerfil->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Area","nombre"=>"tArea","tipo_campo"=>"select","sql"=>$oArea->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Tipo de Usuario","nombre"=>"tTipoUsuario","tipo_campo"=>"select","sql"=>$oTipoUsuario->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  /**
   * Crea el interfaz para actualización
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @param string $id
   * @return string
   */
  function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
    $oPerfil=new c_perfil($this->con);
	$oArea=new c_area($this->con);
	$oTipoUsuario=new c_tipousuario($this->con);
      
    $oAux=new c_usuario($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Username","nombre"=>"tUsername","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->usu_id),
				array("etiqueta"=>"* Clave","nombre"=>"tClave","tipo_campo"=>"password","sql"=>"","valor"=>$oAux->usu_clave),
				array("etiqueta"=>"*  Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->usu_nombre),
				array("etiqueta"=>"* Perfil","nombre"=>"tPerfil","tipo_campo"=>"select","sql"=>$oPerfil->sqlSelect(""),"valor"=>$oAux->per_id),
				array("etiqueta"=>"* Area","nombre"=>"tArea","tipo_campo"=>"select","sql"=>$oArea->sqlSelect(""),"valor"=>$oAux->are_id),
				array("etiqueta"=>"* Tipo de Usuario","nombre"=>"tTipoUsuario","tipo_campo"=>"select","sql"=>$oTipoUsuario->sqlSelect(""),"valor"=>$oAux->tipusu_id),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->usu_mail)
				);
				
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }

  /**
   * Construye código javascript para validación de formularios
   *
   * @return string
   */
  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va
  	
  	<script language="javascript">
	function valida()
	{
  	  define('tUsername', 'string', 'Username',1,20,document);
  	  define('tClave', 'string', 'Clave',1,20,document);
  	  define('tNombre', 'string', 'Nombre',1,100,document);
  	}
  	
  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}
  	
  	function vValidarB(forma,urldestino)
  	{
  	  var res;
  	  res=validate();
  	  if(res) 
  	  {
  	    cambiar_action(forma,urldestino);
  	    forma.submit();
  	  }  	  
  	}
  	
  	</script>
va;
	return($cad);
  }  
  
}


?>