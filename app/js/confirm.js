    function confirmdelete(url) {
        var agree=confirm("Está seguro de eliminar esto?");
        if (agree)
        location.replace(url);
    }
	
    function confirmdeletef() {
        var agree=confirm("Está seguro de eliminar esto?");
        if (agree)
        return true;
		else
		  return false;
    }	

    function confirmunpost(url) {
        var agree=confirm("Are you sure you wish to unpost this?");
        if (agree)
        location.replace(url);
    }

    function confirmemail(url) {
        var agree=confirm("Listo para enviar el e-mail?");
        if (agree)
        location.replace(url);
    }