function chequear_nros(cad)
{
  var i,res;
  
  for (i=0;i<cad.length();i++)
  {
    
  }
}

function valida_ci(caja) 
{ 
  var cad=caja.value;
  var longitud;
  var impar=0,par=0;
  var i,j;
  var aux;
  var errorTam=0;
  
  longitud=cad.length;
  if (longitud==11)
  {
    aux=cad.substring(0,9);
	aux=aux + cad.charAt(10);	
  }
  if (longitud==13)
  {
    aux=cad.substring(0,10);
	//aux=aux + cad.charAt(10);	
  }
  if (longitud==14)
  {
    aux=cad.substring(0,10);
	//aux=aux + cad.charAt(10);	
  }
  if (longitud==10)
  {
    aux=cad;
  }
  
  if((longitud<10)||(longitud>14))
  {
  	errorTam=1;
  }
  
  var dosprimeros=eval(cad.substring(0,2));
  //alert(dosprimeros);
  if((dosprimeros<2)||(dosprimeros>22))
  {
  	errorTam=1;
  }
  
  if(!errorTam)
  {
  
  impar=0;
  par=0;
  var factor=2;
  var impar_aux=0;  
  var resultado=0;
  var valor=0;
  var uno,dos;
  resultado=aux.charAt(9);
  //alert(aux);
  //alert(resultado);
  j=0;
  
  for (i=0;i<9;i++)
  {
    j=i+1;
	valor=aux.charAt(i);
	if((j%2)==1) //impar
	{
	  impar_aux = valor * factor;
	  if(impar_aux>9)
	  {
	    //alert("impar: "+i+" el valor es "+impar_aux + " y es mayor que 9");
		cimpar_aux=impar_aux;
		uno=1;
		dos=impar_aux-10;
		impar=impar+uno+dos;
	  }	
	  else
	  {
	    //alert("impar: "+i+" el valor es "+impar_aux + " y es menor o igual que 9");
		impar=impar+impar_aux;  
	  }	
	}
	else  //par
	{	  
	  par=par+eval(valor);
	  //alert("par: "+i+": el valor es "+ par);
	}
  }
  
  //alert(resultado);
  var v;
  v=eval(impar+par);
  var vpi;
  vpi=parseInt(v/10);
  //alert(vpi);
  vpi=(vpi+1)*10;
  var valor_supe;
  valor_supe=vpi-v;
   
  var datosIguales;
  if (eval(resultado)==valor_supe)
    datosIguales=1;
  else
    datosIguales=0;

  }
  else
  {
  	datosIguales=0;
  }
     
  //alert(v);
  if((datosIguales)&&(!errorTam))
  {
    //alert("son iguales");
	return true;
  }	
  else
  {
    alert("CI / RUC Erróneo!!!");
    caja.value="";
	return false;
  }
  	
	
}
