<?php
		session_start();
		session_unregister('sUsername');
        session_unregister('sPerfil');
        
        session_destroy();
        
        $destino="location:index.php";
		header($destino);
?>
