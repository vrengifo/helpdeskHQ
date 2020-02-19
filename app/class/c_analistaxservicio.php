<?php
/**
 * Administrar la tabla analistaxservicio
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_servicio.php");
include_once("class/c_usuario.php");

class c_analistaxservicio implements c_interfaz 
{
  //atributos base
  var $ser_id;
  var $usu_id;
  var $usu_audit;
  var $usu_faudit;

  /**
   * Variable de conexión a Base de Datos
   *
   * @var unknown_type
   */
  var $con;
  
  var $msg;
  var $separador;
  
  /**
   * Constructor
   *
   * @param conexionBD $conDB
   */
  function __construct(&$conDB,$usuario)
  {
  	$this->con=$conDB;
  	$this->ser_id=0;
  	$this->usu_id="";
  	
  	$this->usu_audit=$usuario;
  	$this->usu_faudit=date("Y-m-d H:i:s");
  	  	 	
  	$this->msg="";
  	$this->separador=":";
  }
  
  function id2cad($servicio,$usuario)
  {
    $res=$servicio.$this->separador.$usuario;
    return($res);
  }
  
  function cad2id($cad)
  {
    list($this->ser_id,$this->usu_id)=explode($this->separador,$cad);
    return ($cad);	
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select ser_id,usu_id from analistaxservicio
	    where usu_id='$this->usu_id' and ser_id='$this->ser_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
	}
	else 
	{
	  $res=$this->id2cad($rs->fields[0],$rs->fields[1]);	
	}
	return($res);	
  }
  
  function info($id)
  { 
    $oAux=new c_analistaxservicio($this->con,$this->usu_audit);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select ser_id,usu_id,usu_audit,usu_faudit 
	    from analistaxservicio
	    where ser_id=$oAux->ser_id and usu_id='$oAux->usu_id' 
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->ser_id=0;
	  $this->usu_id=0;
	  $this->usu_audit=0;
	}
	else 
	{
	  $this->ser_id=$rs->fields[0];
	  $this->usu_id=$rs->fields[1];
	  $this->usu_audit=$rs->fields[2];
	  $this->usu_faudit=$rs->fields[3];
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if(!$existe)
  	{
  		
  	  $sql=<<<va
  	insert into analistaxservicio
  	(ser_id,usu_id,usu_audit,usu_faudit)
  	values
  	('$this->ser_id','$this->usu_id','$this->usu_audit','$this->usu_faudit')
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
  	$oAux=new c_analistaxservicio($this->con,$this->usu_audit);
  	if($oAux->info($id)=="")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update analistaxservicio set
        usu_audit='$this->usu_audit',usu_faudit='$this->usu_faudit' 
        where 
       	ser_id=$oAux->ser_id and usu_id='$oAux->usu_id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$oAux=new c_analistaxservicio($this->con,$this->usu_audit);
  	$oAux->cad2id($id);
  	$sql=<<<sql
  	delete from analistaxservicio 
  	where ser_id=$oAux->ser_id and usu_id='$oAux->usu_id'
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
  	  $cadOrderby=" order by ser_id,usu_id ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select ser_id,usu_id
		from analistaxservicio
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by usu_id"));
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
        $this->ser_id=$dato[0];
	  	$this->usu_id=$dato[1];

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
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	
  	$sql=<<<va
  	  select concat(axs.ser_id,':',axs.usu_id) as id1,s.ser_nombre,u.usu_nombre,concat(axs.ser_id,':',axs.usu_id) as id2 
	  from analistaxservicio axs, servicio s, usuario u 
	  where s.ser_id=axs.ser_id and u.usu_id=axs.usu_id 
	  order by s.ser_nombre,u.usu_nombre
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","Servicio","Usuario","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");

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
  	$oServicio=new c_servicio($this->con);
  	$oUsuario=new c_usuario($this->con);
  	
  	$sqlServicio=$oServicio->sqlSelect("");
  	$sqlUsuario=$oUsuario->sqlSelect("");
  	
    $campo=array(
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>""),
				array("etiqueta"=>"* Usuario","nombre"=>"tUsuario","tipo_campo"=>"select","sql"=>$sqlUsuario,"valor"=>""),
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
    $oAux=new c_analistaxservicio($this->con,$this->usu_audit);
  	$oAux->info($id);
  	
	$oServicio=new c_servicio($this->con);
  	$oUsuario=new c_usuario($this->con);
  	
  	$sqlServicio=$oServicio->sqlSelect("");
  	$sqlUsuario=$oUsuario->sqlSelect("");
  	
    $campo=array(
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>$oAux->ser_id),
				array("etiqueta"=>"* Usuario","nombre"=>"tUsuario","tipo_campo"=>"select","sql"=>$sqlUsuario,"valor"=>$oAux->usu_id),
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