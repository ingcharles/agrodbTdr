<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cm = new ControladorMovilizacionAnimal();
$cantones = $cm->listarLocalizacionLugarEmision($conexion,'CANTONES');
$parroquias = $cm->listarLocalizacionLugarEmision($conexion,'PARROQUIAS');
$coordinaciones = $cm->listarLocalizacionLugarEmision($conexion,'SITIOS');

?>
<header>
	<h1>Nuevo evento de movilización</h1>
</header>
	<form id='nuevoEventoMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarEventoMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="id_area" name="id_area" value="0"/>	
	<input type="hidden" id="identificador_evento" name="identificador_evento" />

	<div id="estado"></div>
		<fieldset id="seleccionarEmisor">
			<legend>Seleccionar evento de movilización</legend>
				<div data-linea="1">
					<label>* Evento que reliza la movilización animal</label>								
			    </div>
			    <div data-linea="2">
					<label>Tipo evento</label>				
					<select id="tipoEmisor" name="tipoEmisor">
						<option value="0">Seleccione...</option>
						<option value="1">Ferias de comercialización</option>
						<option value="2">Centro de exposición</option>
					</select>
					<input type="hidden" id="nombreTipoEvento" name="nombreTipoEvento" />	 
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
					<button type="button" id="btnBusquedaFeria" name="btnBusquedaFeria">Buscar</button>
				</div>			
				<div id="res_feria" data-linea="4"></div>							
	    </fieldset>
        <fieldset id="f">
			<legend>Seleccionar el evento de movilización</legend>		
				<div data-linea="1">
					<label>Evento</label> 
					<input type="text" id="nombre_evento" name="nombre_evento"/>
				</div>	 
				<div data-linea="2">
					<label>Fecha inicio</label> 
					<input type="text" id="fecha_inicio" name="fecha_inicio"/>
				</div>
				<div data-linea="2">
					<label>Fecha fin</label> 
					<input type="text" id="fecha_fin" name="fecha_fin"/>
				</div>																						   		   			   			   		   			   						   		   			   			   		   			   
		</fieldset>			
		<button id="btn_guardar" type="button" name="btn_guardar">Guardar evento</button>
  </form>

<script type="text/javascript">			

$(document).ready(function(){			
		distribuirLineas();	
		$("#fecha_inicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fecha_fin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#funcionario_emisor").hide();
		$("#otro_emisor").hide();	
	});

    //eventos de los botones
    $("#btnBusquedaFeria").click(function(event){
		 event.preventDefault();
		 $('#nuevoEventoMovilizacion').attr('data-opcion','guardarEventoMovilizacion');
		 $('#nuevoEventoMovilizacion').attr('data-destino','res_feria');
	     $('#opcion').val('1');		     	
		 abrir($("#nuevoEventoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio			 		 			 		 	
	});     

    $("#btn_guardar").click(function(event){
		 event.preventDefault();
		 $('#nuevoEventoMovilizacion').attr('data-opcion','guardarEventoMovilizacion');
		 $('#nuevoEventoMovilizacion').attr('data-destino','res_guardar');
	     $('#opcion').val('10');		     	
		 abrir($("#nuevoEventoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 //abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 			 		 	
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
	
</script>