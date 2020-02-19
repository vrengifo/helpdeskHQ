<?php
/**
 * Administrar la tabla logticket
 *
 */
include_once("class/c_interfaz.php");

class c_logticket implements c_interfaz 
{
  //atributos base
  var $logtic_id;
  var $tic_id;
  var $tipacc_id;
  var $tipest_id;
  var $usu_id;
  var $logtic_comentario;
  var $usu_audit;
  var $usu_faudit;
  var $logtic_palabraclave;

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
  	$this->logtic_id=0;
  	$this->tic_id="";
  	$this->tipest_id="";
  	
  	$this->usu_audit=$usuario;
  	$this->usu_faudit=date("Y-m-d H:i:s");
  	 	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select logtic_id from logticket
	    where tic_id='$this->tic_id' 
	    and tipacc_id='$this->tipacc_id' 
	    and tipest_id='$this->tipest_id' 
	    and usu_id='$this->usu_id' 
	    and logtic_comentario='$this->logtic_comentario' 
	    and usu_audit='$this->usu_audit' 
	    and usu_faudit='$this->usu_faudit' 
	    and logtic_palabraclave='$this->logtic_palabraclave'
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
	    select logtic_id,tic_id,tipacc_id,tipest_id,
	    usu_id,logtic_comentario,usu_audit,usu_faudit,
	    logtic_palabraclave  
	    from logticket
	    where logtic_id=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->logtic_id=0;
	  $this->tic_id="";
	}
	else 
	{
	  $this->logtic_id=$rs->fields[0];
	  $this->tic_id=$rs->fields[1];
	  $this->tipacc_id=$rs->fields[2];
	  $this->tipest_id=$rs->fields[3];
	  $this->usu_id=$rs->fields[4];
	  $this->logtic_comentario=$rs->fields[5];
	  $this->usu_audit=$rs->fields[6];
	  $this->usu_faudit=$rs->fields[7];
	  $this->logtic_palabraclave=$rs->fields[8];
	  $res=$id;
	}
	return($res);	
  }
  
  function creaTicket($ticket)
  {
  	$accion="CR";//creacion
  	$estado="A";//asignado
  	
  	$this->tic_id=$ticket;
  	$this->tipacc_id=$accion;
  	$this->tipest_id=$estado;
  	$this->usu_id=$this->usu_audit;
  	$this->logtic_comentario="Ticket creado";
  	$this->logtic_palabraclave="";
  	
  	$res=$this->add();
  	return($res);
  }
  
  function adjuntarArchivo($ticket,$descripcionArchivo,$path)
  {
  	$accion="A";//creacion
  	
  	$this->tic_id=$ticket;
  	$this->tipacc_id=$accion;
  	$this->tipest_id="";
  	$this->usu_id=$this->usu_audit;
  	$this->logtic_comentario='Archivo Adjunto: <a href="'.$path.'" >'.$descripcionArchivo.'</a>';
  	$this->logtic_palabraclave="";
  	
  	$res=$this->add();
  	return($res);
  }
  
  function transferirTicket($ticket,$comentario)
  {
  	$accion="T";//creacion
  	
  	$this->tic_id=$ticket;
  	$this->tipacc_id=$accion;
  	$this->tipest_id="";
  	$this->usu_id=$this->usu_audit;
  	$this->logtic_comentario="Transferencia de ticket:".$comentario;
  	$this->logtic_palabraclave="";
  	
  	$res=$this->add();
  	return($res);
  }
  
  function comentarioUsuario($ticket,$comentario)
  {
  	$accion="U";//creacion
  	
  	$this->tic_id=$ticket;
  	$this->tipacc_id=$accion;
  	$this->tipest_id="";
  	$this->usu_id=$this->usu_audit;
  	$this->logtic_comentario=$comentario;
  	$this->logtic_palabraclave="";
  	
  	$res=$this->add();
  	return($res);
  }
  
  function actualizarEstado($ticket,$estado,$comentario,$palabras)
  {
  	switch ($estado)
  	{
  		case "CA": //cancelado
  			$accion="C";//comentario
  			break;
  		case "CE": //cerrado
  			$accion="S";
  			break;
  		case "EP": //en proceso
  			$accion="C";//comentario
  			break;
  		case "P": //pendiente
  			$accion="C";//comentario
  			break;
  		case "R": //resuelto
  			$accion="S";//solucion
  			break;
  		default:
  			$estado="";
  			$accion="C";
  			break;
  	}
  	$this->tic_id=$ticket;
  	$this->tipacc_id=$accion;
  	$this->tipest_id=$estado;
  	$this->usu_id=$this->usu_audit;
  	$this->logtic_comentario=$comentario;
  	$this->logtic_palabraclave=$palabras;
  	
  	$res=$this->add();
  	return($res);
  }
  
  
  
  function add()
  {
  	$existe=$this->exist();  
  	if(!$existe)
  	{
  		$sql=<<<va
  	insert into logticket
  	(tic_id,tipacc_id,tipest_id,
  	usu_id,logtic_comentario,usu_audit,usu_faudit,
  	logtic_palabraclave)
  	values
  	('$this->tic_id','$this->tipacc_id','$this->tipest_id',
  	'$this->usu_id','$this->logtic_comentario','$this->usu_audit','$this->usu_faudit'
  	,'$this->logtic_palabraclave')
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
  	$oAux=new c_logticket($this->con);
  	if($oAux->info($id)=="0")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->tic_id==$oAux->tic_id)
      {
        $datoNombre=$oAux->tic_id;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="0")
          $datoNombre=$this->tic_id;
        else 
          $datoNombre=$oAux->tic_id;  
      }
      $sql=<<<sql
        update logticket set
        tic_id='$datoNombre',
        tipacc_id='$this->tipacc_id' 
        where 
       	logtic_id=$oAux->logtic_id
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
  	delete from logticket 
  	where logtic_id=$id
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
  	  $cadOrderby=" order by tic_id ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select logtic_id,tic_id
		from logticket
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by tic_id"));
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
        $this->tic_id=$dato[0];
        $this->tipacc_id=$dato[1];

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
	    $this->tic_id=$dato[0];
	    $this->tipacc_id=$dato[1];
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
  	  select logtic_id,tic_id,tipacc_id,logtic_id 
	  from logticket 
	  order by tic_id
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","logticket","Descripcion","Modificar");
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
    $campo=array(
				array("etiqueta"=>"* logticket","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"text","sql"=>"","valor"=>"")
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
    $oAux=new c_logticket($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* logticket","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->tic_id),
				array("etiqueta"=>"* Descripcion","nombre"=>"tNivel","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->tipacc_id),
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
  	  define('tNombre', 'string', 'logticket',1,100,document);
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