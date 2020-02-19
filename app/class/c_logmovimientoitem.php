<?php
/**
 * Administrar la tabla logmovimientoitem
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_usuario.php");
include_once("class/c_item.php");

class c_logmovimientoitem implements c_interfaz 
{
  //atributos base
  var $logmov_id;
  var $ite_id;
  var $tipaccite_id;
  var $usu_id;
  var $logmov_descripcion;
  var $usu_audit;
  var $usu_faudit;

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
  function __construct(&$conDB,$usuario)
  {
  	$this->con=$conDB;
  	$this->logmov_id=0;
  	$this->ite_id="";
  	
  	$this->usu_audit=$usuario;
  	$this->usu_faudit=date("Y-m-d H:i:s");
  	 	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select logmov_id from logmovimientoitem
	    where ite_id='$this->ite_id' 
	    and tipaccite_id='$this->tipaccite_id' 
	    and usu_id='$this->usu_id' 
	    and logmov_descripcion='$this->logmov_descripcion' 
	    and usu_audit='$this->usu_audit' 
	    and usu_faudit='$this->usu_faudit' 
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
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
	    select logmov_id,ite_id,tipaccite_id,
	    usu_id,logmov_descripcion,usu_audit,usu_faudit 
	    from logmovimientoitem
	    where logmov_id=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->logmov_id=0;
	  $this->ite_id="";
	}
	else 
	{
	  $this->logmov_id=$rs->fields[0];
	  $this->ite_id=$rs->fields[1];
	  $this->tipaccite_id=$rs->fields[2];
	  $this->usu_id=$rs->fields[3];
	  $this->logmov_descripcion=$rs->fields[4];
	  $this->usu_audit=$rs->fields[5];
	  $this->usu_faudit=$rs->fields[6];
	  $res=$id;
	}
	return($res);	
  }

  /*
    Funciones tabla itemxusuario
  */
  
  /**
   * Busca en itemxusuario si item esta asignado
   *
   * @param int $item
   * @return int
   */
  function itemAsignado($item)
  {
  	$sql=<<<mya
  	select distinct ite_id 
  	from itemxusuario 
  	where ite_id=$item
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=1;
	return($res);  
  }
  
  /**
   * Busca en itemxusuario si item y usuario estan asignados
   *
   * @param unknown_type $item
   * @param unknown_type $usuario
   * @return unknown
   */
  function itemUsuarioAsignado($item,$usuario)
  {
  	$sql=<<<mya
  	select distinct ite_id 
  	from itemxusuario 
  	where ite_id=$item and usu_id='$usuario'
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	  $res=0;
	else 
	  $res=1;
	return($res);
  }
  
  function itemusuarioInfo($item)
  {
  	$sql=<<<mya
  	select distinct usu_id 
  	from itemxusuario 
  	where ite_id=$item
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	  $res="";
	else 
	  $res=$rs->fields[0];
	return($res);
  }
  

  /**
   * Crea el registro en itemxusuario
   *
   * @return unknown
   */
  function creaItemXUsuario()
  {
  	$sql=<<<mya
	insert into itemxusuario 
	(usu_id,ite_id,usu_audit,usu_faudit) 
	values 
	('$this->usu_id','$this->ite_id','$this->usu_audit','$this->usu_faudit')
mya;
	$rs=&$this->con->Execute($sql);
	return(1);
  }
  
  function eliminaItemXUsuario($item)
  {
  	$sql=<<<mya
	delete from itemxusuario 
	where ite_id='$item' 
mya;
	$rs=&$this->con->Execute($sql);
	return(1);
  }
  
  /*
    Fin Funciones tabla itemxusuario
  */
  
  function asignaItem($item,$usuario,$descripcion)
  {
  	$accion="1";
  	
  	$this->ite_id=$item;
  	$this->tipaccite_id=$accion;
  	$this->usu_id=$usuario;
  	$this->logmov_descripcion="Asignacion: ".$descripcion;
  	
  	if(!$this->itemAsignado($item))
  	{
  	  $res=$this->add();
  	  $this->creaItemXUsuario();
  	  $this->msg="Item asignado satisfactoriamente!!!";
  	}
  	else 
  	{
  	  $this->msg="Item ya fue asignado y no se asignara a ".$usuario;
  	  $res=0;
  	}
  	return($res);
  }
  
  function mantenimientoItem($item,$usuario,$descripcion)
  {
  	$accion="2";
  	
  	$this->ite_id=$item;
  	$this->tipaccite_id=$accion;
  	$this->usu_id=$usuario;
  	$this->logmov_descripcion="Mantenimiento: ".$descripcion;
  	
  	$res=$this->add();
  	$this->msg="Mantenimiento registrado satisfactoriamente!!!";
  	return($res);
  }

  function bajaItem($item,$usuario,$descripcion)
  {
  	$accion="3";
  	
  	$this->ite_id=$item;
  	$this->tipaccite_id=$accion;
  	$this->usu_id=$usuario;
  	$this->logmov_descripcion="Baja: ".$descripcion;
  	
  	$res=$this->add();
  	$this->msg="Item dado de baja!!!";
  	$this->eliminaItemXUsuario($item);
  	
  	$oItem=new c_item($this->con);
  	$oItem->inactivar($item);
  	
  	
  	return($res);
  }
  
  function desasignaItem($item,$usuario,$descripcion)
  {
  	$accion="4"; //Desasignacion
  	
  	$this->ite_id=$item;
  	$this->tipaccite_id=$accion;
  	$this->usu_id=$usuario;
  	$this->logmov_descripcion="Desasignacion: ".$descripcion;
  	
  	if($this->itemAsignado($item))
  	{
  	  $res=$this->add();
  	  $this->eliminaItemXUsuario($item);
  	  $this->msg="Item desasignado satisfactoriamente!!!";
  	}
  	else 
  	{
  	  $this->msg="Item no esta asignado!!!";
  	  $res=0;
  	}
  	return($res);
  }
  
  function reasignaItem($item,$usuario,$descripcion)
  {
  	$accion="5";
  	
  	$this->ite_id=$item;
  	$this->tipaccite_id=$accion;
  	$this->usu_id=$usuario;
  	$this->logmov_descripcion="Reasignacion: ".$descripcion;
  	
  	$usuAnt=$this->itemusuarioInfo($item);
  	
  	$this->desasignaItem($item,$usuAnt,$descripcion);
  	$this->asignaItem($item,$usuario,$descripcion);
  	return(1);
  }
  
  function add()
  {
  	$existe=$this->exist();  
  	if(!$existe)
  	{
  		$sql=<<<va
  	insert into logmovimientoitem
  	(ite_id,tipaccite_id,usu_id,
  	logmov_descripcion,usu_audit,usu_faudit)
  	values
  	('$this->ite_id','$this->tipaccite_id','$this->usu_id',
  	'$this->logmov_descripcion','$this->usu_audit','$this->usu_faudit')
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
  	/*
  	$oAux=new c_logmovimientoitem($this->con);
  	if($oAux->info($id)=="0")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->ite_id==$oAux->ite_id)
      {
        $datoNombre=$oAux->ite_id;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="0")
          $datoNombre=$this->ite_id;
        else 
          $datoNombre=$oAux->ite_id;  
      }
      $sql=<<<sql
        update logmovimientoitem set
        ite_id='$datoNombre',
        tipaccite_id='$this->tipaccite_id' 
        where 
       	logmov_id=$oAux->logmov_id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
    */
  	return($id);
  }
  
  function del($id)
  {
  	/*
  	$sql=<<<sql
  	delete from logmovimientoitem 
  	where logmov_id=$id
sql;
    $rs=&$this->con->Execute($sql);
    */
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by ite_id ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select logmov_id,ite_id
		from logmovimientoitem
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by ite_id"));
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
  
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
        $this->ite_id=$dato[0];
        $this->tipaccite_id=$dato[1];

	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
	    $this->ite_id=$dato[0];
	    $this->tipaccite_id=$dato[1];
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
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
	
	$paramForma="&accion=";
	
	$pfAsigna=$paramForma."1";
	$pfMantenimiento=$paramForma."2";
	$pfBaja=$paramForma."3";
	$pfReasigna=$paramForma."4";
	$pfDesasigna=$paramForma."5";
	
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  
	  <input type="button" name="buAsigna" value="Asignaci&oacute;n" onClick="self.location='$destAdd$param_destino$pfAsigna'">
  	  <input type="button" name="buMantenimiento" value="Mantenimiento" onClick="self.location='$destAdd$param_destino$pfMantenimiento'">
  	  <input type="button" name="buBaja" value="Dada de Baja" onClick="self.location='$destAdd$param_destino$pfBaja'">
  	  <input type="button" name="buReasigna" value="Reasignaci&oacute;n" onClick="self.location='$destAdd$param_destino$pfReasigna'">
  	  <input type="button" name="buDesasigna" value="Desasignaci&oacute;n" onClick="self.location='$destAdd$param_destino$pfDesasigna'">
  	  <input type="button" name="buConsultaA" value="Consulta de Asignaciones Usuario" onClick="fOpenWindow('consultaItemUsuario.php','ConsultaItemUsuario','800','550')">
	  
  	  <br>
va;

  	$fechaDia=date("Y-m-d");
  	$cadMovDia=<<<mya
 and date(l.usu_faudit)='$fechaDia' 
mya;
  	
  	$sql=<<<va
select l.logmov_id,
t.tipaccite_nombre,u.usu_nombre,
concat(i.ite_nombre,'- PN:',i.ite_pn,'- SN:',i.ite_sn) as item,
concat(substring(l.logmov_descripcion,1,100),'...'),l.usu_faudit,
l.logmov_id
from logmovimientoitem l, tipoaccionitem t,usuario u, item i
where
t.tipaccite_id=l.tipaccite_id and u.usu_id=l.usu_id and i.ite_id=l.ite_id 
$cadMovDia 
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("","Acci&oacute;n","Usuario","Item","Descripci&oacute;n","Fecha-Hora","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','80%','true','chc',$destUpdate,$param_destino,"total");

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
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$request=NULL)
  {
    $oItem=new c_item($this->con);
    $oUsu=new c_usuario($this->con);
  	
  	switch ($request["accion"]) 
    {
    	case "1":
  	$campo=array(
				array("etiqueta"=>"* Item","nombre"=>"tItem","tipo_campo"=>"select","sql"=>$oItem->sqlSelect("",0,1),"valor"=>""),
				array("etiqueta"=>"* Usuario","nombre"=>"tUsuario","tipo_campo"=>"select","sql"=>$oUsu->sqlSelectNombre(""),"valor"=>""),
				array("etiqueta"=>" Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
    		break;
    	case "2":
  	$campo=array(
				array("etiqueta"=>"* Item","nombre"=>"tItem","tipo_campo"=>"select","sql"=>$oItem->sqlSelect("",0),"valor"=>""),
				array("etiqueta"=>" Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
    		break;
    	case "3":
  	$campo=array(
				array("etiqueta"=>"* Item","nombre"=>"tItem","tipo_campo"=>"select","sql"=>$oItem->sqlSelect("",0),"valor"=>""),
				array("etiqueta"=>" Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
    		break;
    	case "4":
  	$campo=array(
				array("etiqueta"=>"* Item","nombre"=>"tItem","tipo_campo"=>"select","sql"=>$oItem->sqlSelect("",0,1),"valor"=>""),
				array("etiqueta"=>"* Usuario","nombre"=>"tUsuario","tipo_campo"=>"select","sql"=>$oUsu->sqlSelectNombre(""),"valor"=>""),
				array("etiqueta"=>" Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
    		break;
    	case "5":
  	$campo=array(
				array("etiqueta"=>"* Item","nombre"=>"tItem","tipo_campo"=>"select","sql"=>$oItem->sqlSelect("",0),"valor"=>""),
				array("etiqueta"=>" Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
    		break;
    }
  	

	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"accion","valor"=>$request["accion"]),
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
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
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
    $oAux=new c_logmovimientoitem($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* logmovimientoitem","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ite_id),
				array("etiqueta"=>"* Descripcion","nombre"=>"tNivel","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->tipaccite_id),
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
  	  define('tNombre', 'string', 'logmovimientoitem',1,100,document);
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