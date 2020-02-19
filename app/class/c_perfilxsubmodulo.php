<?php
/**
 * Administrar la tabla perfilxsubmodulo
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_parametro.php");
include_once("class/c_perfil.php");
include_once("class/c_modulo.php");
include_once("class/c_submodulo.php");

class c_perfilxsubmodulo implements c_interfaz 
{
  //atributos base
  var $per_id;
  var $mod_id;
  var $submod_id;
  /**
   * campo per_formulario
   *
   * @var cadena
   */
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
  function __construct($usuario,&$conDB)
  {
  	$this->con=$conDB;
  	$this->per_id=0;
  	$this->mod_id=0;
  	$this->submod_id=0;
  	$this->usu_audit=$usuario;
  	
  	$oPar=new c_parametro($conDB);
  	$this->usu_faudit=date($oPar->fechahoraPHP());
  	
  	$this->msg="";
  	
  	$this->separador=$oPar->par_seplista;
  }
  
  function id2cad($perId,$modId,$submodId)
  {
    $cad=$per_id.$this->separador.$modId.$this->separador.$submodId;
    return($cad);
  }
  
  function cad2id($cad)
  {
    list($this->per_id,$this->mod_id,$this->submod_id)=explode($this->separador,$cad);
  }
  
  function cadQuery($prefijo1,$prefijo2,$prefijo3)
  {
    $cad="(concat(".$prefijo1."per_id,'".$this->separador."',".$prefijo2."mod_id,'".$this->separador."',".$prefijo3."submod_id))";
    return($cad);
  }
  
  function existName($cad)
  { 
  	$oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
//    $cadId=$oAux->cadQuery($this->per_id,$this->mod_id,$this->submod_id);
	$cadId=$oAux->cadQuery("","","");
    $oAux->cad2id($cad);
    
    $sql=<<<vic
	    SELECT $cadId
	    FROM perfilxsubmodulo
	    WHERE per_id=$oAux->per_id AND mod_id=$oAux->mod_id AND submod_id=$oAux->submod_id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	}
	else 
	{
	  $res=$rs->fields[0];	
	}
	return($res);	
  }
  
  function exist()
  { 
  	$oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
//    $cadId=$oAux->cadQuery($this->per_id,$this->mod_id,$this->submod_id);
	$cadId=$oAux->cadQuery("","","");
    $oAux->cad2id($cad);
    $sql=<<<vic
	    select $cadId from perfilxsubmodulo
	    where mod_id='$this->mod_id' and submod_id='$this->submod_id' and per_id='$this->per_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	}
	else 
	{
	  $res=$this->id2cad($this->per_id,$this->mod_id,$this->submod_id);
	}
	return($res);
  }
  
  function info($id)
  { 
    $oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select per_id,mod_id,submod_id,usu_audit,usu_faudit
	    from perfilxsubmodulo 
	    where per_id='$oAux->per_id and 'mod_id='$oAux->mod_id' and submod_id='$oAux->submod_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->per_id=0;
	  $this->mod_id=0;
	  $this->submod_id=0;
	  $this->usu_audit="";
	  $this->usu_faudit="";
	}
	else 
	{
	  $this->per_id=$rs->fields[0];
	  $this->mod_id=$rs->fields[1];
	  $this->submod_id=$rs->fields[2];
	  $this->usu_audit=$rs->fields[3];
	  $this->usu_faudit=$rs->fields[4];
	  $res=$id;
	}
	return($res);	
  }
  
  function create()
  {
    $oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
    
    //$oAux=$this;
      
    $oSub=new c_submodulo($this->con);
    if($oAux->submod_id=="*")
    {
      $submodRS=$oSub->listSubmodule($this->mod_id);
      while(!$submodRS->EOF)
      {
        $submodId=$submodRS->fields[0];
        $oAux->per_id=$this->per_id;
        $oAux->mod_id=$this->mod_id;
        $oAux->submod_id=$submodId;
        $oAux->add();
        $submodRS->MoveNext();
      }
    }
    else 
    {
      $res=$this->add();
    }
    return($res);
  }
  
  function add()
  {
  	$oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
    $oAux->id2cad($this->per_id,$this->mod_id,$this->submod_id);
  	$existe=$this->exist();
  	if($existe=="0")
  	{
  	  $sql=<<<va
  	INSERT INTO perfilxsubmodulo
  	(per_id,mod_id,submod_id,usu_audit,usu_faudit)
  	VALUES
  	('$this->per_id','$this->mod_id','$this->submod_id','$this->usu_audit','$this->usu_faudit')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
      }
      else 
      {
        $res="0";
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
  	$oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->per_nombre==$oAux->per_nombre)
      {
        $datoNombre=$oAux->per_nombre;
      }
      else 
      {
        $existe=$oAux->exist($this->per_nombre);
        if(!$existe)
          $datoNombre=$this->per_nombre;
        else 
          $datoNombre=$oAux->per_nombre;  
      }
      $sql=<<<sql
        UPDATE perfilxsubmodulo SET
        per_id='$per_id',
        usu_audit='$this->usu_audit',
        usu_faudit='$this->usu_faudit'
        where 
        per_id=$id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$oAux=new c_perfilxsubmodulo($this->usu_audit,$this->con);
  	$oAux->cad2id($id);
    $sql=<<<sql
  	DELETE FROM perfilxsubmodulo
  	WHERE per_id='$oAux->per_id' and mod_id='$oAux->mod_id' and submod_id='$oAux->submod_id'
sql;
    $rs=&$this->con->Execute($sql);
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  //chequear
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby="";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select per_id,mod_id,submod_id
		from perfilxsubmodulo
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("ORDER BY per_id"));
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
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->per_id=$dato[0];
	    $this->mod_id=$dato[1];
	    $this->submod_id=$dato[2];
	   /* $this->submod_imagen=$dato[3];
	    $this->submod_orden=$dato[4];*/
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
	    $this->per_id=$dato[0];
	    $this->mod_id=$dato[1];
	    $this->submod_id=$dato[2];
	    /*$this->submod_imagen=$dato[3];
	    $this->submod_orden=$dato[4];*/
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
  	$cadId=$this->cadQuery("pxs.","pxs.","pxs.");
  	$sql=<<<va
  	  select $cadId as A1,
	  p.per_nombre,m.mod_nombre,s.submod_nombre,
	  $cadId as A2
	  from perfilxsubmodulo pxs, perfil p, modulo m, submodulo s
	  where p.per_id=pxs.per_id and m.mod_id=pxs.mod_id and s.submod_id=pxs.submod_id   
	  order by p.per_nombre,m.mod_nombre,s.submod_nombre
va;

  	//echo"<hr>$sql<hr>";
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","Perfil","Módulo","Submódulo","Modificar");
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
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$request=NULL)
  {
    $oPerfil=new c_perfil($this->usu_audit,$this->con);
    
    $oMod=new c_modulo($this->con);
    
    include_once("class/c_submodulo.php");
    $oSub=new c_submodulo($this->con);
    
    if(!isset($request["tModulo"]))
    {
      $rs1=&$this->con->Execute($oMod->sqlSelect(""));
      if(!$rs1->EOF)
        $request["tModulo"]=$rs1->fields[0];
    }
    
    $campo=array(
				array("etiqueta"=>"* Perfil","nombre"=>"tPerfil","tipo_campo"=>"select","sql"=>$oPerfil->sqlSelect(""),"valor"=>$request["tPerfil"]),
				array("etiqueta"=>"* Módulo","nombre"=>"tModulo","tipo_campo"=>"select","sql"=>$oMod->sqlSelect(""),"valor"=>$request["tModulo"],"js"=>"onChange=\"submit();\""),
				array("etiqueta"=>"* Submódulo","nombre"=>"tSubmodulo","tipo_campo"=>"selectAll","sql"=>$oSub->sqlSubmodule($request["tModulo"]),"valor"=>$request["tSubModulo"]),
				/*array("etiqueta"=>"  Imagen","nombre"=>"tImagen","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Orden","nombre"=>"tOrden","tipo_campo"=>"text","sql"=>"","valor"=>"")*/
				
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
  		<form action="perfilxsubmodulo_add.php" method="post" name="form1">
  		  $cadForm
  		  <!--<input type="submit" name="Add" value="Añadir" onClick="return vValidar();">-->
  		  <input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">
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
    $oMod=new c_modulo($this->con);
    
    $oAux=new c_submodulo($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Módulo","nombre"=>"tModulo","tipo_campo"=>"select","sql"=>$oMod->sqlSelect(""),"valor"=>$oAux->mod_id),
				array("etiqueta"=>"* Submódulo","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_nombre),
				array("etiqueta"=>"* Página","nombre"=>"tFormulario","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_formulario),
				array("etiqueta"=>"  Imagen","nombre"=>"tImagen","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_imagen),
				array("etiqueta"=>"* Orden","nombre"=>"tOrden","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_orden)
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
  	  //define('tNombre', 'string', 'Submódulo',1,100,document);
  	  //define('tFormulario', 'string', 'Página',1,100,document);
  	  //define('tOrden', 'num', 'Orden',1,3,document);
  	}
  	
  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}
  	
  	function vValidarB(forma,urldestino)
  	{
  	  //alert(urldestino);
  	  //alert(forma.action);
  	  var res;
  	  res=validate();
  	  //alert(res);
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