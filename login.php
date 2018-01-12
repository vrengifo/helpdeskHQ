<? 
  include('includes/main.php');
  include('adodb/tohtml.inc.php');

  extract($_REQUEST);
  include_once("class/c_usuario.php");

  $client=new c_usuario($conn);

  $res=$client->autenticar($username,$password);

  if($res==$username)
  {
      //registrar las variables de sesion necesarias
    session_start();
	  
	$res=$client->info($username);
	
    $sUsername=$client->usu_id;
    session_register("sUsername");
    
    $sPerfil=$client->per_id;
    session_register("sPerfil");
    
    include_once("includes/header.php");
    buildmenu($sUsername,$sPerfil);
    include_once("includes/footer.php"); 
  }
  	
  else 
  {
  	header("location:index.php?reason=bad&error=ad");
  }
?>