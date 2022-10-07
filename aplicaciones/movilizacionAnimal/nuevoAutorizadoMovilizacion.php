<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cm = new ControladorMovilizacionAnimal();

?>
<header>
	<h1>Nuevo autorizador de movilización</h1>
</header>
	<form id='nuevoAutorizadoMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarAutorizadoMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="id_area" name="id_area" />	
	<input type="hidden" id="identificador_propietario" name="identificador_propietario" />
			
	<div id="estado"></div>
		<fieldset id="seleccionarEmisor1">
			<legend>Búsqueda del propietario</legend>				
				<div data-linea="1">
					<label>* Persona autorizada para realizar trámites de emisión de certificados de movilización animal</label>								
			    </div>			    
			    <div data-linea="2">
					<label>Fecha de autorización</label> 
					<input type="text" id="fecha_autorizacion" name="fecha_autorizacion"/>
				</div>
			    <div id="div1" data-linea="2">
					<label>Buscar :</label> 
					<select id="tipoBusquedaSitio" name="tipoBusquedaSitio">
						<option value="0">Seleccione el sitio....</option>
						<option value="1">Identificación</option>
						<option value="3">Nombre de la granja</option>										
						<option value="2">Apellido del propietario</option>				
					</select>
				</div>
				<div id="div2" data-linea="3">
					<input type="text" id="txtSitioBusqueda" name="txtSitioBusqueda" />							
				</div>
				<div id="div3" data-linea="3">
					<button type="button" id="btnBusquedaSitio" name="btnBusquedaSitio" onclick="buscarSitio()">Buscar</button>
				</div>			
				<div id="res_sitio" data-linea="4"></div>
	    </fieldset>
		<fieldset id="autorizado">
			<legend>Búsqueda del autorizado</legend>
				<div data-linea="1">
					<label>* Seleccionar autorizado de certificados de movilización animal</label>								
			    </div>			    
			    <div data-linea="2">
					<label>Buscar por :</label> 
					<select id="tipoBusqueda" name="tipoBusqueda">
						<option value="0">Seleccionar...</option>
						<option value="1">Identificación</option>	
						<option value="2">Nombre autorizado</option>						
					</select>
				</div>
				<div data-linea="2">					
					<input type="text" id="autorizadoMovilizacion" name="autorizadoMovilizacion" />
				</div>
				<div data-linea="2">
					<button type="button" id="btn_autorizado_movilizacion" name="btn_autorizado_movilizacion">Buscar</button>
				</div>							
				<div id="res_autorizado" data-linea="3"></div>				
				<div data-linea="4">
					<label>Observación :</label> 										
				</div>
				<div data-linea="6">					
					<textarea name="observacion" rows="5" cols="100"></textarea>										
				</div>
	    </fieldset>
       
		<button id="btn_guardar" type="button" name="btn_guardar">Guardar vacunación</button>

  </form>

<script type="text/javascript">			

    $(document).ready(function(){			
		distribuirLineas();	
		$("#fecha_autorizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#funcionario_emisor").hide();
		$("#otro_emisor").hide();	
	});
   
    //eventos de los botones
  	//nuevoAutorizadoMovilizacion guardarAutorizadoMovilizacion
    $("#btnBusquedaSitio").click(function(event){		
		//if($("#estado").html() == ''){		
		 $('#nuevoAutorizadoMovilizacion').attr('data-opcion','guardarAutorizadoMovilizacion');
		 $('#nuevoAutorizadoMovilizacion').attr('data-destino','res_sitio');
	     $('#opcion').val('1');		
		 abrir($("#nuevoAutorizadoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 $('#txtSitioBusqueda').val('');
		//}						 		
	});

    $("#btn_autorizado_movilizacion").click(function(event){		
		//if($("#estado").html() == ''){		
		 $('#nuevoAutorizadoMovilizacion').attr('data-opcion','guardarAutorizadoMovilizacion');
		 $('#nuevoAutorizadoMovilizacion').attr('data-destino','res_autorizado');
	     $('#opcion').val('2');		
		 abrir($("#nuevoAutorizadoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 $('#autorizadoMovilizacion').val('');
		//}						 		
	});

    $("#btn_guardar").click(function(event){
		 event.preventDefault();
		 $('#nuevoAutorizadoMovilizacion').attr('data-opcion','guardarAutorizadoMovilizacion');
		 $('#nuevoAutorizadoMovilizacion').attr('data-destino','res_guardar');
	     $('#opcion').val('10');		     	
		 abrir($("#nuevoAutorizadoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 			 		 	
	});

	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        
		if(!$.trim($("#especie").val())){
			error = true;
			$("#especie").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}

	function buscarSitio(){
		chequearCamposSitio();
	}
		
	function chequearCamposSitio(){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#tipoBusquedaSitio").val()==0){
			error = true;
			$("#tipoBusquedaSitio").addClass("alertaCombo");
		}
		if(!$.trim($("#txtSitioBusqueda").val()) || !esCampoValido("#txtSitioBusqueda")){
			error = true;
			$("#txtSitioBusqueda").addClass("alertaCombo");
		}	
				
		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}
	}//inicio
	
</script>