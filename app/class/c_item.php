<?php
/**
 * Administrar la tabla item
 *
 */
include_once("class/c_interfaz.php");
include_once("adodb/tohtml.inc.php");
include_once("class/c_tipoitem.php");
include_once("class/c_verdaderofalso.php");

class c_item implements c_interfaz 
{
  //atributos base
  var $ite_id;
  var $tipite_id;
  var $ite_id_padre;  
  var $ite_nombre;
  var $ite_descripcion;
  var $ite_pn;
  var $ite_sn;
  var $ite_activo;
  
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
  	$this->ite_id=0;
  	$this->tipite_id="";
  	$this->ite_id_padre="";
  	$this->ite_nombre="";
  	$this->ite_descripcion="";
  	$this->ite_pn="";
  	$this->ite_sn="";
  	$this->ite_activo="";
  	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select ite_id from item
	    where ite_pn='$this->ite_pn' 
	    and ite_sn='$this->ite_sn'
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
	    select ite_id,tipite_id,ite_id_padre,ite_nombre,
	    ite_descripcion,ite_pn,ite_sn,ite_activo 
	    from item
	    where ite_id='$id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->ite_id="0";
	  $this->tipite_id="";
	  $this->ite_id_padre="";
	  $this->ite_nombre="";
	  $this->ite_descripcion="";
	  $this->ite_pn="";
	  $this->ite_sn="";
	  $this->ite_activo="";
	}
	else 
	{
	  $this->ite_id=$rs->fields[0];
	  $this->tipite_id=$rs->fields[1];
	  $this->ite_id_padre=$rs->fields[2];
	  $this->ite_nombre=$rs->fields[3];
	  $this->ite_descripcion=$rs->fields[4];
	  $this->ite_pn=$rs->fields[5];
	  $this->ite_sn=$rs->fields[6];
	  $this->ite_activo=$rs->fields[7];
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if(strlen($existe)==0)
  	{
  	  $sql=<<<va
  	insert into item
  	(tipite_id,ite_id_padre,ite_nombre,
  	ite_descripcion,ite_pn,ite_sn,ite_activo)
  	values
  	('$this->tipite_id','$this->ite_id_padre','$this->ite_nombre',
  	'$this->ite_descripcion','$this->ite_pn','$this->ite_sn','$this->ite_activo')
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
  	$oAux=new c_item($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update item set
        tipite_id='$this->tipite_id',
        ite_id_padre='$this->ite_id_padre',
        ite_nombre='$this->ite_nombre', 
        ite_descripcion='$this->ite_descripcion', 
		ite_activo='$this->ite_activo'
        where 
        ite_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	if($id>0)
  	{
  	  $oAux=new c_item($this->con);
  	  $oAux->info($id);
  	
  	  $sql=<<<sql
  	delete from item 
  	where ite_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
  	}
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby,$conRaiz=0,$activos=0)
  {
  	
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by ite_id ";
  	else 
  	  $cadOrderby=$orderby; 
  	   
  	$cadRaiz="";
  	if($conRaiz==0)
  	{
  	  $cadRaiz=<<<mya
  	and ite_id>0 
mya;
  	}
  	
	$cadActivos="";
  	if($activos==1)
  	{
  	  $cadActivos=<<<mya
  	and ite_activo=1  
mya;
  	}
  	
  	$sql=<<<cad
		select ite_id,concat(ite_nombre,'; PN:',ite_pn,'; SN:',ite_sn) texto
		from item 
		where ite_id_padre=0 
		$cadRaiz 
		$cadActivos 
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by ite_nombre,ite_pn,ite_sn",1));
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
        $this->tipite_id=$dato[0];
	    $this->ite_id_padre=$dato[1];
	    $this->ite_nombre=$dato[2];
	    $this->ite_descripcion=$dato[3];
	    $this->ite_pn=$dato[4];
	    $this->ite_sn=$dato[5];
	    $this->ite_activo=$dato[6];
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
	    $this->tipite_id=$dato[0];
	    $this->ite_id_padre=$dato[1];
	    $this->ite_nombre=$dato[2];
	    $this->ite_descripcion=$dato[3];
	    $this->ite_pn=$dato[4];
	    $this->ite_sn=$dato[5];
	    $this->ite_activo=$dato[6];
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
  	
  	$sql=<<<va
  	  select i.ite_id as uno,
	  ti.tipite_nombre,concat(p.ite_nombre,'-',p.ite_pn,'-',p.ite_sn) as padre,i.ite_nombre,
	  i.ite_pn, i.ite_sn, i.ite_activo,
	  i.ite_id as dos
	  from item i, tipoitem ti , item p
	  where ti.tipite_id=i.tipite_id 
	  and p.ite_id=i.ite_id_padre  
	  $aSql 
	  order by i.ite_id 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","Tipo Item","Padre","Nombre","PN","SN","Activo","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','70%','true','chc',$destUpdate,$param_destino,"total");
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
	$oTipoItem=new c_tipoitem($this->con);
	$oPadre=new c_item($this->con);
	$oVF=new c_verdaderofalso($this->con);
	
    $campo=array(
				array("etiqueta"=>"* Tipo Item","nombre"=>"tTipo","tipo_campo"=>"select","sql"=>$oTipoItem->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Padre","nombre"=>"tPadre","tipo_campo"=>"select","sql"=>$oPadre->sqlSelect("",1),"valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"  Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* PN","nombre"=>"tPn","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* SN","nombre"=>"tSN","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Activo","nombre"=>"tActivo","tipo_campo"=>"select","sql"=>$oVF->sqlSelectSiNo(),"valor"=>"")
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
    $oTipoItem=new c_tipoitem($this->con);
	$oPadre=new c_item($this->con);
	$oVF=new c_verdaderofalso($this->con);
      
    $oAux=new c_item($this->con);
  	$oAux->info($id);
	    $campo=array(
				array("etiqueta"=>"* Tipo Item","nombre"=>"tTipo","tipo_campo"=>"select","sql"=>$oTipoItem->sqlSelect(""),"valor"=>$oAux->tipite_id),
				array("etiqueta"=>"* Padre","nombre"=>"tPadre","tipo_campo"=>"select","sql"=>$oPadre->sqlSelect("",1),"valor"=>$oAux->ite_id_padre),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ite_nombre),
				array("etiqueta"=>"  Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->ite_descripcion),
				array("etiqueta"=>"* PN","nombre"=>"tPn","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ite_pn),
				array("etiqueta"=>"* SN","nombre"=>"tSN","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->ite_sn),
				array("etiqueta"=>"* Activo","nombre"=>"tActivo","tipo_campo"=>"select","sql"=>$oVF->sqlSelectSiNo(),"valor"=>$oAux->ite_activo)
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
  	  define('tNombre', 'string', 'Nombre',1,100,document);
	  define('tPN', 'string', 'PN',1,20,document);
	  define('tSN', 'string', 'SN',1,20,document);
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
  
  function inactivar($id)
  {
  	$oAux=new c_item($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede inactivar";
    }
    else 
    {
      $sql=<<<sql
        update item set
		ite_activo='0'
        where 
        ite_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de inactivacion";

    }
  	return($id);
  }
  
}


?>
