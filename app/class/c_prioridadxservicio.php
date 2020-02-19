<?php
/**
 * Administrar la tabla prioridadxservicio
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_servicio.php");
include_once("class/c_prioridad.php");

class c_prioridadxservicio implements c_interfaz 
{
  //atributos base
  var $ser_id;
  var $pri_id;
  var $prixser_cantidadhoras;

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
  function __construct(&$conDB)
  {
  	$this->con=$conDB;
  	$this->ser_id=0;
  	$this->pri_id="";
  	  	 	
  	$this->msg="";
  	$this->separador=":";
  }
  
  function id2cad($servicio,$prioridad)
  {
    $res=$servicio.$this->separador.$prioridad;
    return($res);
  }
  
  function cad2id($cad)
  {
    list($this->ser_id,$this->pri_id)=explode($this->separador,$cad);
    return ($cad);	
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select ser_id,pri_id from prioridadxservicio
	    where pri_id='$this->pri_id' and ser_id='$this->ser_id'
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
  
  function getNroHoras($prioridad,$servicio)
  {
    $res=$this->info($this->id2cad($servicio,$prioridad));
    if($res==0)
    {
      $oPri=new c_prioridad($this->con);
      $oPri->info($prioridad);
      $nroHoras=$oPri->pri_nrohorasdefault;
	}
	else 
	{
	  $nroHoras=$this->prixser_cantidadhoras;	
	}
	return($nroHoras);
  }
  
  function info($id)
  { 
    $oAux=new c_prioridadxservicio($this->con);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select ser_id,pri_id,prixser_cantidadhoras 
	    from prioridadxservicio
	    where ser_id=$oAux->ser_id and pri_id=$oAux->pri_id 
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->ser_id=0;
	  $this->pri_id=0;
	  $this->prixser_cantidadhoras=0;
	}
	else 
	{
	  $this->ser_id=$rs->fields[0];
	  $this->pri_id=$rs->fields[1];
	  $this->prixser_cantidadhoras=$rs->fields[2];
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
  	insert into prioridadxservicio
  	(ser_id,pri_id,prixser_cantidadhoras)
  	values
  	('$this->ser_id','$this->pri_id','$this->prixser_cantidadhoras')
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
  	$oAux=new c_prioridadxservicio($this->con);
  	if($oAux->info($id)=="")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update prioridadxservicio set
        prixser_cantidadhoras='$this->prixser_cantidadhoras' 
        where 
       	ser_id=$oAux->ser_id and pri_id=$oAux->pri_id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$oAux=new c_prioridadxservicio($this->con);
  	$oAux->cad2id($id);
  	$sql=<<<sql
  	delete from prioridadxservicio 
  	where ser_id=$oAux->ser_id and pri_id=$oAux->pri_id
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
  	  $cadOrderby=" order by ser_id,pri_id ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select ser_id,pri_id
		from prioridadxservicio
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by pri_id"));
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
  	  $ncampos=3;
	  if($ncampos==count($dato))
	  {
        $this->ser_id=$dato[0];
	  	$this->pri_id=$dato[1];
        $this->prixser_cantidadhoras=$dato[2];

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
	    $this->ser_id=$dato[0];
	  	$this->pri_id=$dato[1];
        $this->prixser_cantidadhoras=$dato[2];
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
  	  select concat(pxs.ser_id,':',pxs.pri_id) as id1,s.ser_nombre,p.pri_nombre,pxs.prixser_cantidadhoras, concat(pxs.ser_id,':',pxs.pri_id) as id2 
	  from prioridadxservicio pxs, servicio s, prioridad p 
	  where s.ser_id=pxs.ser_id and p.pri_id=pxs.pri_id 
	  order by s.ser_nombre,p.pri_nombre
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","Servicio","Prioridad","Nro. Horas","Modificar");
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
  	$oPrioridad=new c_prioridad($this->con);
  	
  	$sqlServicio=$oServicio->sqlSelect("");
  	$sqlPrioridad=$oPrioridad->sqlSelect("");
  	
    $campo=array(
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>""),
				array("etiqueta"=>"* Prioridad","nombre"=>"tPrioridad","tipo_campo"=>"select","sql"=>$sqlPrioridad,"valor"=>""),
				array("etiqueta"=>"* Nro. Horas","nombre"=>"tHoras","tipo_campo"=>"text","sql"=>"","valor"=>"")
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
    $oAux=new c_prioridadxservicio($this->con);
  	$oAux->info($id);
  	
	$oServicio=new c_servicio($this->con);
  	$oPrioridad=new c_prioridad($this->con);
  	
  	$sqlServicio=$oServicio->sqlSelect("");
  	$sqlPrioridad=$oPrioridad->sqlSelect("");
  	
    $campo=array(
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>$oAux->ser_id),
				array("etiqueta"=>"* Prioridad","nombre"=>"tPrioridad","tipo_campo"=>"select","sql"=>$sqlPrioridad,"valor"=>$oAux->pri_id),
				array("etiqueta"=>"* Nro. Horas","nombre"=>"tHoras","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->prixser_cantidadhoras)
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
  	  define('tHoras', 'string', 'Nro. Horas',1,100,document);
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