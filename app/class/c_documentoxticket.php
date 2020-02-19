<?php
/**
 * Administrar la tabla documentoxticket
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_ticket.php");
include_once("class/c_logticket.php");

class c_documentoxticket implements c_interfaz 
{
  //atributos base
  var $doc_id;
  var $tic_id;
  var $doc_nombre;
  var $doc_descripcion;
  var $doc_path;
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
  	$this->doc_id=0;
  	$this->tic_id=0;
  	$this->doc_nombre="";
  	$this->doc_descripcion="";
  	$this->doc_path="";
  	$this->usu_audit=$usuario;
  	$this->usu_faudit=date("Y-m-d H:i:s");

  	$this->msg="";
  	$this->separador=":";
  }
  
  function id2cad($ticket,$doc)
  {
    $res=$ticket.$this->separador.$doc;
    return($res);
  }
  
  function cad2id($cad)
  {
    list($this->tic_id,$this->doc_id)=explode($this->separador,$cad);
    return ($cad);	
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select tic_id,doc_id from documentoxticket
	    where doc_nombre='$this->doc_nombre' and tic_id='$this->tic_id'
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
    $oAux=new c_documentoxticket($this->con);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select tic_id,doc_id,doc_nombre,doc_descripcion,
	    doc_path,usu_audit,usu_faudit  
	    from documentoxticket
	    where doc_id=$oAux->doc_id and tic_id=$oAux->tic_id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->doc_id=0;
	  $this->tic_id=0;
	  $this->doc_nombre="";
	}
	else 
	{
	  $this->tic_id=$rs->fields[0];
	  $this->doc_id=$rs->fields[1];
	  $this->doc_nombre=$rs->fields[2];
	  $this->doc_descripcion=$rs->fields[3];
	  $this->doc_path=$rs->fields[4];
	  $this->usu_audit=$rs->fields[5];
	  $this->usu_faudit=$rs->fields[6];
	  
	  $res=$id;
	}
	return($res);	
  }
  
  function siguienteDocId($ticId)
  {
  	$sql=<<<mya
  	select max(doc_id) 
  	from documentoxticket 
  	where tic_id=$ticId 
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=1;
	}
	else 
	{
	  $res=$rs->fields[0]+1;
	}
	return($res); 
  }
  
  function generaNombreArchivo($ticId,$docId,$nombreDocumento)
  {
  	$res=$ticId."_".$docId."_".$nombreDocumento;
  	return($res); 
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if($existe=="")
  	{
  	  //siguiente doc_id
  	  $this->doc_id=$this->siguienteDocId($this->tic_id);
  	  $this->doc_nombre=$this->generaNombreArchivo($this->tic_id,$this->doc_id,$this->doc_nombre);
  	  $this->doc_path.=$this->doc_nombre;
  	  
  	  $sql=<<<va
  	insert into documentoxticket
  	(tic_id,doc_id,doc_nombre,doc_descripcion,
  	doc_path,usu_audit,usu_faudit)
  	values
  	('$this->tic_id','$this->doc_id','$this->doc_nombre','$this->doc_descripcion',
  	'$this->doc_path','$this->usu_audit','$this->usu_faudit')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
  	    $oLog=new c_logticket($this->con,$this->usu_audit);
  	    $oLog->adjuntarArchivo($this->tic_id,$this->doc_descripcion,$this->doc_path);
      }
      else 
      {
        $res="";
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
  	$oAux=new c_documentoxticket($this->con);
  	if($oAux->info($id)=="")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->doc_nombre==$oAux->doc_nombre)
      {
        $datoNombre=$oAux->doc_nombre;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="")
          $datoNombre=$this->doc_nombre;
        else 
          $datoNombre=$oAux->doc_nombre;  
      }
      $sql=<<<sql
        update documentoxticket set
        doc_nombre='$datoNombre'
        where 
       	doc_id=$oAux->doc_id and tic_id=$oAux->tic_id
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
  	$oAux=new c_documentoxticket($this->con);
  	$oAux->cad2id($id);
  	
  	$sql=<<<sql
  	delete from documentoxticket 
  	where doc_id=$oAux->doc_id and tic_id=$oAux->tic_id 
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
  	  $cadOrderby=" order by doc_nombre ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select concat(tic_id,':',doc_id) as id,doc_nombre
		from documentoxticket
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by doc_nombre"));
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
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
        $this->doc_nombre=$dato[0];

	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=0;
	  if($ncampos==count($dato))
	  {
        $this->doc_nombre=$dato[0];
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
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$idPadre="0")
  {
	//mostrar la info del ticket
	$oTicket=new c_ticket($this->con);
	$oTicket->info($idPadre);
  	$campo=array(
				array("etiqueta"=>" Ticket","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oTicket->tic_id),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Ticket","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";

  	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idPadre;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">
	  <input type="hidden" name="idp" value="$idPadre">
	  <!--
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	
  	$sql=<<<va
  	  select concat(p.tic_id,':',p.doc_id) as id1,p.tic_id,p.doc_nombre,p.doc_descripcion,concat(p.tic_id,':',p.doc_id) as id2 
	  from documentoxticket p
	  order by p.doc_id
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array(" ","Ticket #","Archivo","Descripcion","Ver");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");

	  $cextra="id_aplicacion|id_subaplicacion|principal|idp";
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
	//mostrar la info del ticket
	$oTicket=new c_ticket($this->con);
	$oTicket->info($request["idp"]);
  	$campo=array(
				array("etiqueta"=>" Ticket","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oTicket->tic_id),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Ticket","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";

    $campo=array(
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDesc","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Archivo","nombre"=>"tArchivo","tipo_campo"=>"file","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal),
					array("nombre"=>"idp","valor"=>$request["idp"])
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$idPadre;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1" enctype="multipart/form-data" >
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
function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id,$request=null)  
  {
    $oAux=new c_documentoxticket($this->con);
  	$oAux->info($id);
  	
  	//mostrar la info de la encuesta
	$oTicket=new c_ticket($this->con);
	$oTicket->info($oAux->tic_id);
  	$campo=array(
				array("etiqueta"=>" Ticket","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oTicket->tic_id),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Ticket","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";
  	
    $campo=array(
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->doc_nombre),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDesc","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->doc_descripcion),
				array("etiqueta"=>"* Archivo","nombre"=>"tArchivo","tipo_campo"=>"file","sql"=>"","valor"=>$oAux->doc_path),
				);
				
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
			  		array("nombre"=>"idp","valor"=>$request["idp"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$request["idp"];
	$param_documentoxtickets=$param_destino;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1" enctype="multipart/form-data" >
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
  	  define('tNombre', 'string', 'Nombre',1,1000,document);
  	  define('tArchivo', 'string', 'Archivo',1,250,document);
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